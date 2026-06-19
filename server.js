const express = require("express");
const cors = require("cors");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const { Pool } = require("pg");
require("dotenv").config();

const app = express();

app.use(express.json());

app.use(
  cors({
    origin: [
      "http://localhost:5500",
      "http://127.0.0.1:5500",
      "http://192.168.66.88:5500",
       "http://10.72.12.179:5500",
      process.env.FRONTEND_URL,
    ].filter(Boolean),
     methods: ["GET", "POST", "PUT", "DELETE"],
    allowedHeaders: ["Content-Type", "Authorization"],
  })
);

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: {
    rejectUnauthorized: false,
  },
});

app.get("/", async (req, res) => {
  try {
    await pool.query("select now()");
    res.json({
      success: true,
      message: "Backend RSIA aktif dan database terhubung.",
    });
  } catch (error) {
    console.error("DATABASE ERROR:", error);
    res.status(500).json({
      success: false,
      message: "Backend aktif, tetapi database belum terhubung.",
    });
  }
});

app.post("/register", async (req, res) => {
  try {
    const { nama, email, telp, password } = req.body;

    if (!nama || !email || !telp || !password) {
      return res.status(400).json({
        success: false,
        message: "Nama, email, nomor telepon, dan password wajib diisi.",
      });
    }

    const existingUser = await pool.query(
      "select id from nakes_users where lower(email) = lower($1)",
      [email]
    );

    if (existingUser.rows.length > 0) {
      return res.status(409).json({
        success: false,
        message: "Email sudah terdaftar.",
      });
    }

    const passwordHash = await bcrypt.hash(password, 10);

    const result = await pool.query(
      `insert into nakes_users
        (nama, email, telp, password_hash, role, status_verifikasi)
       values
        ($1, $2, $3, $4, $5, $6)
       returning id, nama, email, telp, role, status_verifikasi, created_at`,
      [nama, email, telp, passwordHash, "nakes", "menunggu"]
    );

    return res.status(201).json({
      success: true,
      message: "Registrasi berhasil dan data masuk ke database.",
      user: result.rows[0],
    });
  } catch (error) {
    console.error("REGISTER ERROR:", error);
    return res.status(500).json({
      success: false,
      message: "Terjadi kesalahan saat registrasi.",
    });
  }
});

app.post("/login", async (req, res) => {
  try {
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({
        success: false,
        message: "Email dan password wajib diisi.",
      });
    }

    const result = await pool.query(
      "select * from nakes_users where lower(email) = lower($1)",
      [email]
    );

    if (result.rows.length === 0) {
      return res.status(401).json({
        success: false,
        message: "Email tidak ditemukan.",
      });
    }

    const user = result.rows[0];

    const passwordValid = await bcrypt.compare(password, user.password_hash);

    if (!passwordValid) {
      return res.status(401).json({
        success: false,
        message: "Password salah.",
      });
    }

    const token = jwt.sign(
      {
        id: user.id,
        email: user.email,
        role: user.role,
      },
      process.env.JWT_SECRET,
      {
        expiresIn: "2h",
      }
    );

    return res.json({
      success: true,
      message: "Login berhasil.",
      token,
      user: {
        id: user.id,
        nama: user.nama,
        email: user.email,
        telp: user.telp,
        role: user.role,
        status_verifikasi: user.status_verifikasi,
      },
    });
  } catch (error) {
    console.error("LOGIN ERROR:", error);
    return res.status(500).json({
      success: false,
      message: "Terjadi kesalahan saat login.",
    });
  }
});

function verifyToken(req, res, next) {
  const authHeader = req.headers.authorization;

  if (!authHeader) {
    return res.status(401).json({
      success: false,
      message: "Token tidak ditemukan.",
    });
  }

  const token = authHeader.split(" ")[1];

  try {
    req.user = jwt.verify(token, process.env.JWT_SECRET);
    next();
  } catch (error) {
    return res.status(403).json({
      success: false,
      message: "Token tidak valid atau sudah kedaluwarsa.",
    });
  }
}

app.get("/me", verifyToken, async (req, res) => {
  try {
    const result = await pool.query(
      `select 
        u.id, u.nama, u.email, u.telp, u.role, u.status_verifikasi,
        p.nik, p.jenis_kelamin, p.tempat_lahir, p.tanggal_lahir,
        p.alamat, p.jabatan, p.departemen, p.nomor_str, p.nomor_sip,
        p.tanggal_gabung, p.status_pegawai, p.bio, p.foto_url
       from nakes_users u
       left join nakes_profiles p on p.user_id = u.id
       where u.id = $1`,
      [req.user.id]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({
        success: false,
        message: "User tidak ditemukan.",
      });
    }

    return res.json({
      success: true,
      user: result.rows[0],
    });
  } catch (error) {
    console.error("ME ERROR:", error);
    return res.status(500).json({
      success: false,
      message: "Terjadi kesalahan saat mengambil data user.",
    });
  }
});
app.post("/register-full", async (req, res) => {
  const client = await pool.connect();

  try {
    const {
      nama,
      email,
      telp,
      password,

      nik,
      jenis_kelamin,
      tempat_lahir,
      tanggal_lahir,
      alamat,

      jabatan,
      departemen,
      nomor_str,
      nomor_sip,
      tanggal_gabung,
      status_pegawai,
      bio,

      pendidikan,
      pekerjaan,
    } = req.body;

    if (!nama || !email || !telp || !password) {
      return res.status(400).json({
        success: false,
        message: "Nama, email, nomor telepon, dan password wajib diisi.",
      });
    }

    if (!nik || !jenis_kelamin || !tempat_lahir || !tanggal_lahir || !alamat) {
      return res.status(400).json({
        success: false,
        message: "Data diri wajib dilengkapi.",
      });
    }

    if (!jabatan || !departemen || !status_pegawai) {
      return res.status(400).json({
        success: false,
        message: "Profil dan jabatan wajib dilengkapi.",
      });
    }

    await client.query("BEGIN");

    const existingUser = await client.query(
      "select id from nakes_users where lower(email) = lower($1)",
      [email]
    );

    if (existingUser.rows.length > 0) {
      await client.query("ROLLBACK");

      return res.status(409).json({
        success: false,
        message: "Email sudah terdaftar.",
      });
    }

    const passwordHash = await bcrypt.hash(password, 10);

    const userResult = await client.query(
      `insert into nakes_users
        (nama, email, telp, password_hash, role, status_verifikasi)
       values
        ($1, $2, $3, $4, $5, $6)
       returning id, nama, email, telp, role, status_verifikasi, created_at`,
      [nama, email, telp, passwordHash, "nakes", "menunggu"]
    );

    const user = userResult.rows[0];

    await client.query(
      `insert into nakes_profiles
        (
          user_id,
          nik,
          jenis_kelamin,
          tempat_lahir,
          tanggal_lahir,
          alamat,
          jabatan,
          departemen,
          nomor_str,
          nomor_sip,
          tanggal_gabung,
          status_pegawai,
          bio,
          foto_url
        )
       values
        ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14)`,
      [
        user.id,
        nik,
        jenis_kelamin,
        tempat_lahir,
        tanggal_lahir,
        alamat,
        jabatan,
        departemen,
        nomor_str || null,
        nomor_sip || null,
        tanggal_gabung || null,
        status_pegawai,
        bio || null,
        null,
      ]
    );

    if (Array.isArray(pendidikan)) {
      for (const item of pendidikan) {
        if (
          item.institusi ||
          item.jenjang ||
          item.jurusan ||
          item.tahun_lulus
        ) {
          await client.query(
            `insert into nakes_pendidikan
              (user_id, jenjang, institusi, jurusan, tahun_lulus)
             values
              ($1, $2, $3, $4, $5)`,
            [
              user.id,
              item.jenjang || null,
              item.institusi || null,
              item.jurusan || null,
              item.tahun_lulus || null,
            ]
          );
        }
      }
    }

    if (Array.isArray(pekerjaan)) {
      for (const item of pekerjaan) {
        if (
          item.instansi ||
          item.posisi ||
          item.tahun_mulai ||
          item.tahun_selesai ||
          item.deskripsi
        ) {
          await client.query(
            `insert into nakes_pekerjaan
              (user_id, instansi, posisi, tahun_mulai, tahun_selesai, deskripsi)
             values
              ($1, $2, $3, $4, $5, $6)`,
            [
              user.id,
              item.instansi || null,
              item.posisi || null,
              item.tahun_mulai || null,
              item.tahun_selesai || null,
              item.deskripsi || null,
            ]
          );
        }
      }
    }

    await client.query("COMMIT");

    return res.status(201).json({
      success: true,
      message: "Pendaftaran lengkap berhasil. Data masuk ke database.",
      user,
    });
  } catch (error) {
    await client.query("ROLLBACK");

    console.error("REGISTER FULL ERROR:", error);

    return res.status(500).json({
      success: false,
      message: "Terjadi kesalahan saat menyimpan pendaftaran lengkap.",
    });
  } finally {
    client.release();
  }
});
const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
  console.log(`Backend berjalan di port ${PORT}`);
});