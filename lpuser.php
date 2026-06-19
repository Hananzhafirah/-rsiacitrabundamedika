<?php session_start(); ?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TBC-Care | Solusi Terpadu Penanggulangan TBC</title>

    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style1.css" />
    <link rel="stylesheet" href="styleheader.css" />
    <link rel="stylesheet" href="style-booking.css" />

    <style>
      /* --- Tambahan CSS untuk Tampilan VA & Status --- */
      #va-display-box {
        margin-top: 15px;
        padding: 20px;
        background: #f0f7ff;
        border: 2px dashed #053f5c;
        border-radius: 12px;
        text-align: center;
        animation: fadeIn 0.3s ease;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }

        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .va-number {
        font-size: 22px;
        font-weight: bold;
        color: #053f5c;
        letter-spacing: 2px;
        font-family: "Courier New", Courier, monospace;
        margin: 10px 0;
        display: block;
      }

      .status-completed {
        background: #d1fae5;
        color: #065f46;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
      }

      .status-pending {
        background: #fff3cd;
        color: #856404;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
      }

      .copy-btn {
        background: none;
        border: none;
        color: #053f5c;
        font-size: 13px;
        cursor: pointer;
        text-decoration: underline;
        font-weight: 600;
      }
    </style>
  </head>

  <body>
    <nav class="navbar">
      <div class="nav-container">
        <div class="nav-brand">
          <img
            src="logo.jpeg"
            alt="Logo TBC"
            class="nav-logo"
            style="height: 50px"
          />
        </div>
        <div class="nav-links">
          <a href="/lpumum.html" class="nav-item">Beranda</a>
          <a href="/Untitled-1.html" class="nav-item">Edukasi</a>
          <?php if(isset($_SESSION['status_login']) && $_SESSION['status_login'] == true): ?>
          
          <button class="btn-login" style="background-color: #053f5c; color: white;">
            Halo, <?php echo $_SESSION['nama_lengkap']; ?>
          </button>
          <a href="logout.php" class="nav-item" style="color: #d32f2f; margin-left:10px; font-weight:bold;">Keluar</a>
          
      <?php else: ?>
          
          <button class="btn-login" onclick="location.href = 'register.html'">
            Daftar
          </button>
          
      <?php endif; ?>
        </div>
      </div>
    </nav>

    <main class="dashboard-content">
      <section class="action-section" id="section-services">
        <h2 class="section-title">Ajukan Pelayanan Baru</h2>
        <div class="service-cards">
          <div class="service-card">
            <i class="fa-solid fa-microscope icon"></i>
            <h3>Tes & Konsultasi</h3>
            <p>Skrining awal dan konsultasi spesialis TBC.</p>
            <button class="btn-apply" onclick="showServiceMenu()">
              Pesan Sekarang
            </button>
          </div>
          <div class="service-card">
            <i class="fa-solid fa-bed-pulse icon"></i>
            <h3>Rawat Inap</h3>
            <p>Fasilitas inap intensif di mitra rumah sakit.</p>
            <button class="btn-apply" onclick="startRawatInap()">
              Pesan Sekarang
            </button>
          </div>
          <div class="service-card">
            <i class="fa-solid fa-stethoscope icon"></i>
            <h3>Rawat Jalan</h3>
            <p>Pemeriksaan rutin tanpa menginap.</p>
            <button class="btn-apply" onclick="startRawatJalan()">
              Pesan Sekarang
            </button>
          </div>
        </div>
      </section>

      <!-- PILIH LAYANAN -->
      <section id="section-service-type" class="hidden">
        <div class="booking-flow-container">
          <h3 class="step-title">Pilih Jenis Layanan</h3>
          <div class="method-options">
            <div class="method-card" onclick="startConsultation()">
              <i class="fa-solid fa-stethoscope"></i>
              <h4>Konsultasi Dokter</h4>
              <p>Konsultasi dengan dokter spesialis TBC</p>
            </div>
            <div class="method-card" onclick="startTestFlow()">
              <i
                class="fa-solid fa-microscope"
                style="color: #053f5c; font-size: 32px"
              ></i>
              <h4>Ajukan Tes TBC</h4>
              <p>Pemeriksaan Kultur, ZN, atau TCM</p>
            </div>
          </div>
          <button class="btn-text-cancel" onclick="backHome()">Kembali</button>
        </div>
      </section>

      <!-- FLOW KONSULTASI -->
      <section id="section-booking" class="hidden">
        <div class="booking-flow-container">
          <div class="progress-stepper">
            <div class="step-item active" id="s-0">1. Metode</div>
            <div class="step-item" id="s-1">2. Jadwal</div>
            <div class="step-item" id="s-2">3. Bayar</div>
          </div>

          <div id="step-0">
            <h3 class="step-title">Pilih Metode Konsultasi</h3>
            <div class="method-options">
              <div class="method-card" onclick="selectMethod('Online')">
                <i class="fa-solid fa-comment-dots" style="color: #053f5c"></i>
                <h4>Konsultasi Online</h4>
                <p>Konsultasi interaktif via room chat.</p>
              </div>
              <div class="method-card" onclick="selectMethod('Offline')">
                <i class="fa-solid fa-hospital-user"></i>
                <h4>Konsultasi Offline</h4>
                <p>Datang langsung ke Mitra Kesehatan.</p>
              </div>
            </div>
            <button class="btn-text-cancel" onclick="hideBookingFlow()">
              Batalkan
            </button>
          </div>

        <div id="step-1" class="hidden">
  <h3 class="step-title">Pilih Dokter & Jam Tersedia</h3>
  <div class="doctor-option">
    <div class="dr-header">
      <div class="dr-identity">
        <h4>dr. Siti Aminah, Sp.P</h4>
        <p id="selected-method-label" style="color: #053f5c; font-weight: bold"></p>
      </div>
      <span class="price-tag" style="color: #053f5c">Rp 150.000</span>
    </div>

    <div style="margin: 15px 0;">
      <input type="date" id="booking-date" class="form-control" 
             style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
    </div>
    <div class="time-grid">
      <button class="time-slot" onclick="selectTime('09:00', this)">09:00</button>
      <button class="time-slot" onclick="selectTime('10:30', this)">10:30</button>
      <button class="time-slot" onclick="selectTime('13:00', this)">13:00</button>
    </div>
  </div>
  
<button class="btn-apply" onclick="goToStep(2)">
  Lanjut ke Pembayaran
</button>
  <button class="btn-text-cancel" onclick="goToStep(0)">
    Kembali
  </button>
</div>

          <div id="step-2" class="hidden">
            <h3 class="step-title">Metode Pembayaran</h3>
            <div class="payment-cards-group">
              <div
                class="payment-card payment-card-interactive"
                id="card-qris"
                onclick="selectPaymentMethod('qris')"
              >
                <input type="radio" name="pay" id="pay_qris" checked />
                <label for="pay_qris">
                  <i class="fa-solid fa-qrcode"></i>
                  QRIS / E-Wallet (Gopay, OVO, Dana)
                </label>
              </div>

              <div id="qris-image-area" class="qris-container">
                <div class="qris-frame">
                  <img src="qrisdummy.jpg" alt="QRIS" class="qris-img" />
                </div>
                <p class="qris-instruction">
                  Pindai kode QR di atas untuk membayar
                </p>
              </div>

              <div
                class="payment-card payment-card-interactive"
                id="card-va"
                onclick="selectPaymentMethod('va')"
              >
                <input type="radio" name="pay" id="pay_va" />
                <label for="pay_va">
                  <i
                    class="fa-solid fa-building-columns"
                    style="color: #053f5c"
                  ></i>
                  Virtual Account Bank
                </label>
              </div>

              <div id="va-banks-area" class="va-banks-list hidden">
                <label
                  class="bank-item"
                  onclick="showVANumber('BCA', '8801 0812 3456 7890')"
                  ><input type="radio" name="bank" /> Bank BCA</label
                >
                <label
                  class="bank-item"
                  onclick="showVANumber('Mandiri', '9001 2233 4455 6677')"
                  ><input type="radio" name="bank" /> Bank Mandiri</label
                >
                <label
                  class="bank-item"
                  onclick="showVANumber('BNI', '8271 0001 2223 3344')"
                  ><input type="radio" name="bank" /> Bank BNI</label
                >
                <label
                  class="bank-item"
                  onclick="showVANumber('BRI', '1022 0000 1111 2222')"
                  ><input type="radio" name="bank" /> Bank BRI</label
                >

                <div id="va-display-box" class="hidden">
                  <p style="font-size: 13px; color: #666">
                    Nomor VA
                    <span id="target-bank" style="font-weight: bold"></span>:
                  </p>
                  <span class="va-number" id="va-number-text"></span>
                  <button
                    class="copy-btn"
                    onclick="alert('Nomor rekening disalin!')"
                  >
                    <i class="fa-regular fa-copy"></i> Salin Kode
                  </button>
                </div>
              </div>
            </div>

            <div
              class="payment-summary"
              style="border-left-color: #053f5c; margin-top: 20px"
            >
              <p>
                Total Bayar: <strong style="color: #053f5c">Rp 150.000</strong>
              </p>
            </div>
            <button class="btn-apply" onclick="goToStep(3)">
              Konfirmasi & Bayar
            </button>
            <button class="btn-text-cancel" onclick="goToStep(1)">
              Kembali
            </button>
          </div>

          <div id="step-3" class="hidden success-state">
            <i
              class="fa-solid fa-circle-check"
              style="color: #053f5c; font-size: 60px"
            ></i>
            <h3 style="color: #053f5c; margin-top: 15px">
              Pembayaran Berhasil!
            </h3>
            <p style="line-height: 1.6; color: #666">
              Jadwal konsultasi Anda telah tersimpan. Silakan cek riwayat Anda.
            </p>
            <button
              class="btn-apply"
              onclick="finishOrder()"
              style="margin-top: 20px; padding: 12px 40px"
            >
              Tutup
            </button>
          </div>
        </div>
      </section>

<!--- FLOW PENGAJUAN TES --->
        <!-- FLOW AJUKAN TES -->
        <section id="section-test" class="hidden">
            <div class="booking-flow-container">
                <div class="progress-stepper">
                    <div class="step-item active" id="t-0">1. Tes</div>
                    <div class="step-item " id="t-1">2. RS</div>
                    <div class="step-item " id="t-2">3. Jadwal</div>
                    <div class="step-item " id="t-3">4. Bayar</div>
                </div>

                <!-- STEP 1 PILIH TES -->
                <div id="test-step-0">
                    <h3 class="step-title">Pilih Jenis Tes TBC</h3>
                    <div class="method-options">
                        <div class="method-card" onclick="selectTest('Kultur')">
                            <i class="fa-solid fa-flask"></i>
                            <h4>Tes Kultur</h4>
                            <p>Pemeriksaan paling akurat</p>
                        </div>
                        <div class="method-card" onclick="selectTest('ZN')">
                            <i class="fa-solid fa-microscope"></i>
                            <h4>Pewarnaan ZN</h4>
                            <p>Pemeriksaan mikroskopis</p>
                        </div>
                        <div class="method-card" onclick="selectTest('TCM')">
                            <i class="fa-solid fa-dna"></i>
                            <h4>Tes Molekuler TCM</h4>
                            <p>Deteksi DNA bakteri TBC</p>
                        </div>
                    </div>
                    <button class="btn-text-cancel" onclick="backHome()">Batalkan</button>
                </div>

                <!-- STEP 2 PILIH RS -->
                <div id="test-step-1" class="hidden">
                    <h3 class="step-title">Pilih Rumah Sakit</h3>
                    <div class="doctor-option" onclick="selectHospital('RSUD Depok Jaya')">
                        <h4>RSUD Depok Jaya</h4>
                        <p>Tersedia Tes TBC</p>
                    </div>
                    <div class="doctor-option" onclick="selectHospital('RS Bhayangkara')">
                        <h4>RS Bhayangkara</h4>
                        <p>Tersedia Tes TBC</p>
                    </div>
                    <div class="doctor-option" onclick="selectHospital('RS Mitra Keluarga')">
                        <h4>RS Mitra Keluarga</h4>
                        <p>Tersedia Tes TBC</p>
                    </div>
                    <button class="btn-text-cancel" onclick="goTestStep(0)">Kembali</button>
                </div>

                <!-- STEP 3 PILIH JAM -->
                 <div id="test-step-2" class="hidden">
                    <h3 class="step-title">Pilih Tanggal & Jam Kedatangan</h3>
                    <!-- TANGGAL -->
                     <input type="date" id="test-tanggal" class="input-field">
                     <!-- JAM -->
                      <div class="time-grid" style="margin-top:15px;">
                        <button class="time-slot" onclick="selectTestTime('09:00',this)">09:00</button>
                        <button class="time-slot" onclick="selectTestTime('11:00',this)">11:00</button>
                        <button class="time-slot" onclick="selectTestTime('14:00',this)">14:00</button>
                    </div>
                    <button class="btn-apply" onclick="goTestStep(3)">Lanjut Pembayaran</button>
                    <button class="btn-text-cancel" onclick="goTestStep(1)">Kembali</button>
                </div>

                <!-- STEP 4 PEMBAYARAN TES -->
                <div id="test-step-3" class="hidden">
                    <h3 class="step-title">Metode Pembayaran</h3>
                    <div class="payment-cards-group">
                        <!-- QRIS -->
                        <div class="payment-card payment-card-interactive" onclick="selectTestPayment('qris')">
                            <input type="radio" name="paytest" id="paytest_qris" checked>
                            <label for="paytest_qris">
                                <i class="fa-solid fa-qrcode"></i>
                                QRIS / E-Wallet (Gopay, OVO, Dana)
                            </label>
                        </div>
                        <!-- QR IMAGE -->
                        <div id="test-qris-area" class="qris-container">
                            <div class="qris-frame">
                                <img src="qrisdummy.jpg" class="qris-img">
                            </div>
                            <p class="qris-instruction">Pindai kode QR untuk membayar</p>
                        </div>
                        <!-- VA -->
                        <div class="payment-card payment-card-interactive" onclick="selectTestPayment('va')">
                            <input type="radio" name="paytest" id="paytest_va">
                            <label for="paytest_va">
                                <i class="fa-solid fa-building-columns"></i>
                                Virtual Account Bank
                            </label>
                        </div>
                        <!-- BANK LIST -->
                        <div id="test-va-area" class="va-banks-list hidden">
                            <label class="bank-item" onclick="showTestVA('BCA','8801 1111 2222 3333')">
                                <input type="radio" name="banktest"> Bank BCA
                            </label>
                            <label class="bank-item" onclick="showTestVA('Mandiri','9001 4444 5555 6666')">
                                <input type="radio" name="banktest"> Bank Mandiri
                            </label>
                            <label class="bank-item" onclick="showTestVA('BNI','8271 7777 8888 9999')">
                                <input type="radio" name="banktest"> Bank BNI
                            </label>
                            <label class="bank-item" onclick="showTestVA('BRI','1022 0000 1111 2222')">
                                <input type="radio" name="banktest"> Bank BRI
                            </label>
                            <div id="test-va-display" class="hidden">
                                <p>Nomor VA <span id="test-bank"></span>:</p>
                                <span class="va-number" id="test-va-number"></span>
                                <button class="copy-btn" onclick="alert('Nomor rekening disalin!')"><i
                                        class="fa-regular fa-copy"></i> Salin Kode</button>
                            </div>
                        </div>
                    </div>
                    <!-- TOTAL -->
                    <div class="payment-summary">
                        <p>Total Bayar: <strong id="test-price"></strong></p>
                    </div>
                    <button class="btn-apply" onclick="goTestStep(4)">Konfirmasi & Bayar</button>
                    <button class="btn-text-cancel" onclick="goTestStep(2)">Kembali</button>
                </div>

                <!-- STEP 5 SUCCESS TEST-->
                <div id="test-step-4" class="hidden success-state">
                    <i class="fa-solid fa-circle-check"></i>
                    <h3>Pembayaran Berhasil!</h3>
                    <p>Jadwal tes Anda telah tersimpan.</p>
                    <button class="btn-apply" onclick="finishTestOrder()">Tutup</button>
                </div>
            </div>
            
        <!-- RAWAT INAP -->
      </section>
      <section id="section-rawat-inap" class="hidden">
        <div class="booking-flow-container">
          <div class="progress-stepper">
            <div class="step-item active" id="stepper-ri-0">1. Tujuan</div>
            <div class="step-item" id="stepper-ri-1">2. RS</div>
            <div class="step-item" id="stepper-ri-2">3. Kelas</div>
            <div class="step-item" id="stepper-ri-3">4. Bayar</div>
          </div>

          <div id="ri-step-0">
            <h3 class="step-title">Tujuan Rawat Inap</h3>
            <input
              type="text"
              id="ri-tujuan"
              placeholder="Contoh: Pengobatan TBC Intensif"
              class="input-field"
            />
            <button class="btn-apply" onclick="goRiStep(1)">Lanjut</button>
            <button class="btn-text-cancel" onclick="closeRawatInap()">
              Kembali
            </button>
          </div>

          <div id="ri-step-1" class="hidden">
             <h3 class="step-title">Tanggal Masuk</h3>
            <input
                type="date"
                id="rj-date"
                class="input-field"
                style="margin-top: 10px"
              />
            <h3 class="step-title">Pilih Rumah Sakit</h3>
            <div class="doctor-option" onclick="selectRS(this, 'RSUD Depok')">
              RSUD Depok
            </div>
            <div
              class="doctor-option"
              onclick="selectRS(this, 'RS Mitra Keluarga')"
            >
              RS Mitra Keluarga
            </div>
            <button class="btn-apply" onclick="goRiStep(2)">Lanjut</button>
            <button class="btn-text-cancel" onclick="goRiStep(1)">
              Kembali
            </button>
          </div>

          <div id="ri-step-2" class="hidden">
            <h3 class="step-title">Pilih Kelas</h3>
            <div class="method-options">
              <div class="method-card" onclick="selectKelas(this, 'Kelas 1')">
                Kelas 1
              </div>
              <div class="method-card" onclick="selectKelas(this, 'Kelas 2')">
                Kelas 2
              </div>
              <div class="method-card" onclick="selectKelas(this, 'Kelas 2')">
                Kelas 3
              </div>
              <div class="method-card" onclick="selectKelas(this, 'VIP')">
                VIP
              </div>
            </div>
            <button class="btn-apply" onclick="goRiStep(3)">Lanjut</button>
            <button class="btn-text-cancel" onclick="goRiStep(2)">
              Kembali
            </button>
          </div>

          <div id="ri-step-3" class="hidden">
            <h3 class="step-title">Metode Pembayaran DP</h3>
            <div class="payment-cards-group">
              <div
                class="payment-card payment-card-interactive"
                id="ri-card-qris"
                onclick="selectRIPaymentMethod('qris')"
              >
                <input type="radio" name="ri_pay" id="ri_pay_qris" checked />
                <label for="ri_pay_qris">
                  <i class="fa-solid fa-qrcode"></i>
                  QRIS / E-Wallet (Gopay, OVO, Dana)
                </label>
              </div>

              <div id="ri-qris-image-area" class="qris-container">
                <div class="qris-frame">
                  <img src="qrisdummy.jpg" alt="QRIS" class="qris-img" />
                </div>
                <p class="qris-instruction">
                  Pindai kode QR di atas untuk membayar DP
                </p>
              </div>

              <div
                class="payment-card payment-card-interactive"
                id="ri-card-va"
                onclick="selectRIPaymentMethod('va')"
              >
                <input type="radio" name="ri_pay" id="ri_pay_va" />
                <label for="ri_pay_va">
                  <i
                    class="fa-solid fa-building-columns"
                    style="color: #053f5c"
                  ></i>
                  Virtual Account Bank
                </label>
              </div>

              <div id="ri-va-banks-area" class="va-banks-list hidden">
                <label
                  class="bank-item"
                  onclick="showRIVANumber('BCA', '8801 0812 3456 0000')"
                >
                  <input type="radio" name="ri_bank" /> Bank BCA
                </label>
                <label
                  class="bank-item"
                  onclick="showRIVANumber('Mandiri', '9001 2233 4455 0000')"
                >
                  <input type="radio" name="ri_bank" /> Bank Mandiri
                </label>
                <label
                  class="bank-item"
                  onclick="showRIVANumber('BNI', '8271 0001 2223 0000')"
                >
                  <input type="radio" name="ri_bank" /> Bank BNI
                </label>
                <label
                  class="bank-item"
                  onclick="showRIVANumber('BRI', '1022 0000 1111 0000')"
                >
                  <input type="radio" name="ri_bank" /> Bank BRI
                </label>

                <div id="ri-va-display-box" class="hidden">
                  <p style="font-size: 13px; color: #666">
                    Nomor VA
                    <span id="ri-target-bank" style="font-weight: bold"></span>:
                  </p>
                  <span class="va-number" id="ri-va-number-text"></span>
                  <button
                    class="copy-btn"
                    onclick="alert('Nomor VA Rawat Inap disalin!')"
                  >
                    <i class="fa-regular fa-copy"></i> Salin Kode
                  </button>
                </div>
              </div>
            </div>

            <div
              id="ri-keterangan"
              style="
                margin-top: 15px;
                font-size: 16px;
                color: rgb(44, 43, 43);
                line-height: 1.4;
              "
            >
              *Pembayaran awal (DP) berlaku untuk 1 hari pertama. Sisa tagihan
              akan dihitung saat check-out rumah sakit.
            </div>

            <div
              class="payment-summary"
              style="border-left-color: #053f5c; margin-top: 20px"
            >
              <p>
                Total Bayar (DP):
                <strong style="color: #053f5c">Rp 150.000</strong>
              </p>
            </div>

            <button class="btn-apply" onclick="riFinishOrder()">
              Konfirmasi & Bayar
            </button>
            <button class="btn-text-cancel" onclick="goRiStep(2)">
              Kembali
            </button>
          </div>

          <div id="ri-step-4" class="hidden success-state">
            <i
              class="fa-solid fa-circle-check"
              style="color: #053f5c; font-size: 60px"
            ></i>
            <h3>Registrasi Berhasil!</h3>
            <p>
              Silakan datang ke bagian administrasi RS pilihan Anda dengan
              menunjukkan bukti bayar ini.
            </p>
            <button class="btn-apply" onclick="closeRawatInap()">Tutup</button>
          </div>
        </div>
      </section>

      <!-- RAWAT JALANN -->
      <section id="section-rawat-jalan" class="hidden">
        <div class="booking-flow-container">
          <div class="progress-stepper">
            <div class="step-item active" id="rj-s-0">1. Perawat</div>
            <div class="step-item" id="rj-s-1">2. Jadwal</div>
            <div class="step-item" id="rj-s-2">3. Bayar</div>
          </div>

          <div id="rj-step-0">
            <h3 class="step-title">Pilih Perawat Homecare</h3>
            <div class="method-options">
              <div
                class="method-card"
                onclick="selectNurse('Ns. Tiara', 250000)"
              >
                <i class="fa-solid fa-user-nurse"></i>
                <h4>Ns. Tiara</h4>
                <p>Spesialis TBC - Beji</p>
                <small>Rp 250.000 / Kunjungan</small>
              </div>
              <div
                class="method-card"
                onclick="selectNurse('Ns. Rizal', 200000)"
              >
                <i class="fa-solid fa-user-nurse"></i>
                <h4>Ns. Rizal</h4>
                <p>Umum - Margonda</p>
                <small>Rp 200.000 / Kunjungan</small>
              </div>
            </div>
            <button class="btn-text-cancel" onclick="closeRawatJalan()">
              Batalkan
            </button>
          </div>

          <div id="rj-step-1" class="hidden">
            <h3 class="step-title">Tentukan Jadwal Kunjungan</h3>
            <div class="doctor-option">
              <p>Perawat Terpilih: <strong id="rj-display-nurse"></strong></p>
              <input
                type="date"
                id="rj-date"
                class="input-field"
                style="margin-top: 10px"
              />
              <div class="time-grid" style="margin-top: 15px">
                <button class="time-slot" onclick="selectRJTime('09:00', this)">
                  09:00
                </button>
                <button class="time-slot" onclick="selectRJTime('13:00', this)">
                  13:00
                </button>
                <button class="time-slot" onclick="selectRJTime('16:00', this)">
                  16:00
                </button>
              </div>
            </div>
            <button class="btn-apply" onclick="rjNext(2)">
              Lanjut ke Pembayaran
            </button>
            <button class="btn-text-cancel" onclick="rjNext(0)">Kembali</button>
          </div>

          <div id="rj-step-2" class="hidden">
            <h3 class="step-title">Metode Pembayaran</h3>
            <div class="payment-cards-group">
              <div
                class="payment-card payment-card-interactive"
                id="rj-card-qris"
                onclick="selectRJPaymentMethod('qris')"
              >
                <input type="radio" name="rj_pay" id="rj_pay_qris" checked />
                <label for="rj_pay_qris">
                  <i class="fa-solid fa-qrcode"></i>
                  QRIS / E-Wallet (Gopay, OVO, Dana)
                </label>
              </div>

              <div id="rj-qris-image-area" class="qris-container">
                <div class="qris-frame">
                  <img src="qrisdummy.jpg" alt="QRIS" class="qris-img" />
                </div>
                <p class="qris-instruction">
                  Pindai kode QR di atas untuk membayar
                </p>
              </div>

              <div
                class="payment-card payment-card-interactive"
                id="rj-card-va"
                onclick="selectRJPaymentMethod('va')"
              >
                <input type="radio" name="rj_pay" id="rj_pay_va" />
                <label for="rj_pay_va">
                  <i
                    class="fa-solid fa-building-columns"
                    style="color: #053f5c"
                  ></i>
                  Virtual Account Bank
                </label>
              </div>

              <div id="rj-va-banks-area" class="va-banks-list hidden">
                <label
                  class="bank-item"
                  onclick="showRJVANumber('BCA', '8801 0812 3456 7890')"
                  ><input type="radio" name="rj_bank" /> Bank BCA</label
                >
                <label
                  class="bank-item"
                  onclick="showRJVANumber('Mandiri', '9001 2233 4455 6677')"
                  ><input type="radio" name="rj_bank" /> Bank Mandiri</label
                >
                <label
                  class="bank-item"
                  onclick="showRJVANumber('BNI', '8271 0001 2223 3344')"
                  ><input type="radio" name="rj_bank" /> Bank BNI</label
                >
                <label
                  class="bank-item"
                  onclick="showRJVANumber('BRI', '1022 0000 1111 2222')"
                  ><input type="radio" name="rj_bank" /> Bank BRI</label
                >

                <div id="rj-va-display-box" class="hidden">
                  <p style="font-size: 13px; color: #666">
                    Nomor VA
                    <span id="rj-target-bank" style="font-weight: bold"></span>:
                  </p>
                  <span class="va-number" id="rj-va-number-text"></span>
                  <button
                    class="copy-btn"
                    onclick="alert('Nomor rekening disalin!')"
                  >
                    <i class="fa-regular fa-copy"></i> Salin Kode
                  </button>
                </div>
              </div>
            </div>

            <div
              class="payment-summary"
              style="border-left-color: #053f5c; margin-top: 20px"
            >
              <p>
                Total Bayar:
                <strong id="rj-total-price-label" style="color: #053f5c"
                  >Rp 0</strong
                >
              </p>
            </div>
            <button class="btn-apply" onclick="rjFinishOrder()">
              Konfirmasi & Bayar
            </button>
            <button class="btn-text-cancel" onclick="rjNext(1)">Kembali</button>
          </div>

          <div id="rj-step-3" class="hidden success-state">
            <i
              class="fa-solid fa-circle-check"
              style="color: #053f5c; font-size: 60px"
            ></i>
            <h3>Pemesanan Berhasil!</h3>
            <p>Perawat akan datang sesuai jadwal yang dipilih.</p>
            <button class="btn-apply" onclick="closeRawatJalan()">Tutup</button>
          </div>
        </div>
      </section>

      <section class="history-section">
        <h2 class="section-title">Riwayat Pemesanan & Pelayanan</h2>
        <div class="table-container">
          <table class="history-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal Pemesanan</th>
                <th>Mitra Kesehatan</th>
                <th>Layanan</th>
                <th>Jadwal Pelaksanaan</th>
                <th>Harga</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="history-data">
              <tr>
                <td>1</td>
                <td>RSUD Depok Jaya</td>
                <td>Konsultasi Online</td>
                <td>10 Mar 2026</td>
                <td>Rp 150.000</td>
                <td><span class="status-completed">Selesai</span></td>
              </tr>
              <tr id="empty-row" class="hidden">
                <td
                  colspan="6"
                  style="text-align: center; color: #999; padding: 30px"
                >
                  Belum ada riwayat pemesanan.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <div>
                <img src="LogoPD.png" alt="PeduliTBDepok Logo" class="footer-logo">
                <p class="footer-desc">Membangun ekosistem kesehatan digital yang inklusif untuk masa depan Indonesia tanpa TBC.</p>
            </div>
            <div>
                <h4 class="footer-title-small">Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="#">Pusat Bantuan</a></li>
                    <li><a href="#">Privasi & Data</a></li>
                    <li><a href="#">Kerja Sama Instansi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title-small">Kontak Darurat</h4>
                <p class="footer-contact"><i class="fa-solid fa-phone"></i> Hotline TBC: 088806474921</p>
                <p class="footer-contact"><i class="fa-solid fa-envelope"></i> faridayeniasih@mail.ugm.ac.id</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 PeduliTBDepok Platform. Seluruh Hak Cipta Dilindungi.
        </div>
    </footer>

    <script>
      let selectedTimeValue = "";
      let selectedMethodType = "";

      function showBookingFlow() {
        document.getElementById("section-services").classList.add("hidden");
        document.getElementById("section-booking").classList.remove("hidden");
        window.scrollTo({ top: 0, behavior: "smooth" });
      }

      function hideBookingFlow() {
        document.getElementById("section-services").classList.remove("hidden");
        document.getElementById("section-booking").classList.add("hidden");
        goToStep(0);
      }

      function selectMethod(method) {
        selectedMethodType = method;
        document.getElementById("selected-method-label").innerText =
          "Metode: Konsultasi " + method;
        goToStep(1);
      }

      function selectTime(time, btn) {
        selectedTimeValue = time;
        document
          .querySelectorAll(".time-slot")
          .forEach((b) => b.classList.remove("selected"));
        btn.classList.add("selected");
      }

      function goToStep(n) {
        if (n === 2 && selectedTimeValue === "") {
          alert("Silakan pilih jam terlebih dahulu!");
          return;
        }
        [0, 1, 2, 3].forEach((i) => {
          const el = document.getElementById("step-" + i);
          if (el) el.classList.add("hidden");
        });
        document.getElementById("step-" + n).classList.remove("hidden");

        document.querySelectorAll(".step-item").forEach((item, idx) => {
          item.classList.toggle("active", idx <= n);
        });
      }

      function selectPaymentMethod(methodType) {
        document.getElementById("qris-image-area").classList.add("hidden");
        document.getElementById("va-banks-area").classList.add("hidden");
        document.getElementById("va-display-box").classList.add("hidden");
        if (methodType === "qris") {
          document.getElementById("qris-image-area").classList.remove("hidden");
          document.getElementById("pay_qris").checked = true;
        } else if (methodType === "va") {
          document.getElementById("va-banks-area").classList.remove("hidden");
          document.getElementById("pay_va").checked = true;
        }
      }

      function showVANumber(bank, number) {
        const vaBox = document.getElementById("va-display-box");
        vaBox.classList.remove("hidden");
        document.getElementById("target-bank").innerText = bank;
        document.getElementById("va-number-text").innerText = number;
      }

      function getTodayDate(){
const today = new Date()

return today.toLocaleDateString('id-ID',{
day:'2-digit',
month:'short',
year:'numeric'
})
}

function finishOrder(){
const tbody = document.getElementById("history-data")
const tanggalPesan = getTodayDate()
const jadwal =
document.getElementById("booking-date").value + " • " + selectedTimeValue
const row = `
<tr>
<td></td>
<td>${tanggalPesan}</td>
<td>Klinik TBC-Care</td>
<td>Konsultasi ${selectedMethodType}</td>
<td>${jadwal}</td>
<td>Rp 150.000</td>
<td><span class="status-pending">Dijadwalkan</span></td>
</tr>
`
tbody.insertAdjacentHTML("afterbegin", row)
updateTableNumbers()
hideBookingFlow()
selectedTimeValue=""
selectedMethodType=""
}

// ========== PENGAJUAN TES ==========
        let selectedTest = "";
        let selectedTestPrice = 0;
        let selectedTestTime = "";
        let selectedHospital = "";
        let selectedTestDate = "";

        function showServiceMenu() {
            document.getElementById('section-services').classList.add('hidden')
            document.getElementById('section-service-type').classList.remove('hidden')
        }
        
        function backHome(){
            document.getElementById('section-services').classList.remove('hidden')
            document.getElementById('section-service-type').classList.add('hidden')
            document.getElementById('section-booking').classList.add('hidden')
            document.getElementById('section-test').classList.add('hidden')
            goTestStep(0)
            window.scrollTo({top:0, behavior:'smooth'})
        }

function startConsultation() {

    document.getElementById('section-service-type').classList.add('hidden')
    document.getElementById('section-booking').classList.remove('hidden')

    // RESET SEMUA STEP
    [0,1,2,3].forEach(i=>{
        document.getElementById("step-"+i).classList.add("hidden")
    })

    //BALIK KE STEP AWAL
    document.getElementById("step-0").classList.remove("hidden")

    //RESET STEPPER UI
    document.querySelectorAll("#section-booking .step-item")
        .forEach((item,idx)=>{
            item.classList.toggle("active", idx===0)
        })

    // RESET DATA
    selectedTimeValue = ""
    selectedMethodType = ""
}
        function startTestFlow() {
            document.getElementById('section-service-type').classList.add('hidden')
            document.getElementById('section-test').classList.remove('hidden')
        }
        
        function goTestStep(n){
            if(n === 1 && !selectedTest){
                alert("Pilih jenis tes dulu!")
                return
            }
            if(n === 2 && !selectedHospital){
                alert("Pilih rumah sakit dulu!")
                return
            }
            if(n === 3){
                selectedTestDate = document.getElementById('test-tanggal').value
                if(!selectedTestDate){
                    alert("Pilih tanggal dulu!")
                    return
                }
                if(!selectedTestTime){
                    alert("Pilih jam dulu!")
                    return
                }
            }
            // sembunyikan semua step
            [0,1,2,3,4].forEach(i=>{
                let el = document.getElementById('test-step-'+i)
                if(el) el.classList.add('hidden')
            })
            // tampilkan step aktif
            document.getElementById('test-step-'+n).classList.remove('hidden')
            // update stepper
            document.querySelectorAll('#section-test .step-item').forEach((item,index)=>{
                item.classList.toggle('active', index <= n)
            })
        }

        function selectTest(test) {
            selectedTest = test
            // SET HARGA DINAMIS
            if (test === "Kultur") selectedTestPrice = 200000
            if (test === "ZN") selectedTestPrice = 120000
            if (test === "TCM") selectedTestPrice = 300000
            // tampilkan harga ke UI
            document.getElementById('test-price').innerText =
                "Rp " + selectedTestPrice.toLocaleString('id-ID')
            goTestStep(1)

        }

        function selectTestTime(time, btn) {
            selectedTestTime = time
            document.querySelectorAll('#section-test .time-slot')
                .forEach(b => b.classList.remove('selected'))
            btn.classList.add('selected')
        }

        function selectHospital(hospital) {
            selectedHospital = hospital
            goTestStep(2)
        }

        function selectTestPayment(method) {
            document.getElementById('test-qris-area').classList.add('hidden')
            document.getElementById('test-va-area').classList.add('hidden')
            document.getElementById('test-va-display').classList.add('hidden')
            if (method === 'qris') {
                document.getElementById('test-qris-area').classList.remove('hidden')
                document.getElementById('paytest_qris').checked = true
            }
            if (method === 'va') {
                document.getElementById('test-va-area').classList.remove('hidden')
                document.getElementById('paytest_va').checked = true
            }
        }

        function showTestVA(bank, number) {
            document.getElementById('test-va-display').classList.remove('hidden')
            document.getElementById('test-bank').innerText = bank
            document.getElementById('test-va-number').innerText = number
        }
        
function finishTestOrder(){
const tbody = document.getElementById('history-data')
const tanggalPesan = getTodayDate()
const tanggalPelaksanaan =
new Date(selectedTestDate).toLocaleDateString('id-ID',{
day:'numeric',
month:'short',
year:'numeric'
}) + " • " + selectedTestTime

const row = `
<tr>
<td></td>
<td>${tanggalPesan}</td>
<td>${selectedHospital}</td>
<td>Tes ${selectedTest}</td>
<td>${tanggalPelaksanaan}</td>
<td>Rp ${selectedTestPrice.toLocaleString('id-ID')}</td>
<td><span class="status-pending">Dijadwalkan</span></td>
</tr>
`

tbody.insertAdjacentHTML('afterbegin', row)

updateTableNumbers()

// RESET
selectedTest=""
selectedHospital=""
selectedTestTime=""
selectedTestDate=""
document.getElementById('test-tanggal').value=""
document.querySelectorAll('.selected').forEach(el=>el.classList.remove('selected'))

backHome()
}

        function updateTableNumbers() {
            const rows = document.querySelectorAll('#history-data tr')
            rows.forEach((row, i) => {
                if (row.cells[0]) row.cells[0].innerText = i + 1
            })
        }

      // ================= RAWAT INAP =================
      let riData = {
        tujuan: "",
        kelas: "",
        rs: "",
      };

      function startRawatInap() {
        document.getElementById("section-services").classList.add("hidden");
        document
          .getElementById("section-rawat-inap")
          .classList.remove("hidden");
        goRiStep(0); // Selalu mulai dari step awal
      }

       function selectRS(element, rs) {
        riData.rs = rs;
        // Hapus highlight dari semua kartu, lalu tambahkan ke kartu yang dipilih
        document
          .querySelectorAll("#ri-step-1 .doctor-option")
          .forEach((el) => el.classList.remove("active-selection"));
        element.classList.add("active-selection");
      }

      function selectKelas(element, kelas) {
        riData.kelas = kelas;
        // Hapus highlight dari semua kartu, lalu tambahkan ke kartu yang dipilih
        document
          .querySelectorAll("#ri-step-2 .method-card")
          .forEach((el) => el.classList.remove("active-selection"));
        element.classList.add("active-selection");
      }

      function goRiStep(n) {
        // VALIDASI SEBELUM MAJU (Forward Validation)
        if (n === 1) {
          riData.tujuan = document.getElementById("ri-tujuan").value.trim();
          if (!riData.tujuan) {
            alert("Isi tujuan rawat inap terlebih dahulu!");
            return;
          }
        }
        if (n === 2 && !riData.rs) {
          alert("Pilih RS terlebih dahulu!");
          return;
        }
        if (n === 3 && !riData.kelas) {
          alert("Pilih kelas terlebih dahulu!");
          return;
        }

        // SEMBUNYIKAN SEMUA STEP
        [0, 1, 2, 3, 4].forEach((i) => {
          const el = document.getElementById("ri-step-" + i);
          if (el) el.classList.add("hidden");
        });

        // TAMPILKAN STEP AKTIF
        const targetStep = document.getElementById("ri-step-" + n);
        if (targetStep) targetStep.classList.remove("hidden");

        // UPDATE STEPPER PROGRESS (Hanya untuk step 0 sampai 3)
        document
          .querySelectorAll("#section-rawat-inap .step-item")
          .forEach((item, index) => {
            if (index <= n) {
              item.classList.add("active");
            } else {
              item.classList.remove("active");
            }
          });
      }

      function selectRIPaymentMethod(method) {
        const qrisArea = document.getElementById("ri-qris-image-area");
        const vaArea = document.getElementById("ri-va-banks-area");

        // Auto-select radio button based on div click
        if (method === "qris")
          document.getElementById("ri_pay_qris").checked = true;
        if (method === "va")
          document.getElementById("ri_pay_va").checked = true;

        if (method === "qris") {
          qrisArea.classList.remove("hidden");
          vaArea.classList.add("hidden");
        } else {
          qrisArea.classList.add("hidden");
          vaArea.classList.remove("hidden");
        }
      }

      function showRIVANumber(bank, number) {
        document.getElementById("ri-va-display-box").classList.remove("hidden");
        document.getElementById("ri-target-bank").innerText = bank;
        document.getElementById("ri-va-number-text").innerText = number;
      }

function riFinishOrder(){
goRiStep(4)
const tbody = document.getElementById("history-data")
const tanggalPesan = getTodayDate()
const jadwal = "Check-in Rawat Inap"
const row = `
<tr>
<td></td>
<td>${tanggalPesan}</td>
<td>${riData.rs}</td>
<td>Rawat Inap (${riData.kelas})</td>
<td>${jadwal}</td>
<td>Rp 150.000</td>
<td><span class="status-pending">Diproses</span></td>
</tr>
`
tbody.insertAdjacentHTML("afterbegin", row)
updateTableNumbers()

}

      function closeRawatInap() {
        document.getElementById("section-rawat-inap").classList.add("hidden");
        const serviceSection = document.getElementById("section-services");
        if (serviceSection) serviceSection.classList.remove("hidden");

        // Opsional: Reset form untuk booking selanjutnya
        document.getElementById("ri-tujuan").value = "";
        riData = { tujuan: "", kelas: "", rs: "" };
        document
          .querySelectorAll(".active-selection")
          .forEach((el) => el.classList.remove("active-selection"));
      }

      // ================= RAWAT JALAN (HOME VISIT) =================
      let rjData = {
        nurse: "",
        price: 0,
        time: "",
        date: "",
      };

      function startRawatJalan() {
        // Sembunyikan dashboard utama, tampilkan section rawat jalan
        document.getElementById("section-services").classList.add("hidden");
        document
          .getElementById("section-rawat-jalan")
          .classList.remove("hidden");
        rjNext(0); // Mulai dari step pertama (Pilih Perawat)
      }

      function selectRJTime(time, btn) {
        rjData.time = time;
        // Hapus class selected dari tombol lain di grup yang sama
        document
          .querySelectorAll("#rj-step-1 .time-slot")
          .forEach((b) => b.classList.remove("selected"));
        btn.classList.add("selected");
      }

      function rjNext(step) {
        // Validasi sederhana untuk ke tahap pembayaran
        if (step === 2) {
          rjData.date = document.getElementById("rj-date").value;
          if (!rjData.date || !rjData.time) {
            alert("Silakan pilih tanggal dan jam kunjungan terlebih dahulu!");
            return;
          }
        }

        // Sembunyikan semua sub-step Rawat Jalan
        document
          .querySelectorAll('[id^="rj-step-"]')
          .forEach((el) => el.classList.add("hidden"));
        // Tampilkan sub-step yang dituju
        document.getElementById("rj-step-" + step).classList.remove("hidden");

        // Update Stepper Progress
        document.querySelectorAll('[id^="rj-s-"]').forEach((item, idx) => {
          item.classList.toggle("active", idx <= step);
        });
      }

      function rjFinishOrder(){
const tbody = document.getElementById("history-data")
const tanggalPesan = getTodayDate()
const jadwal =
rjData.date + " • " + rjData.time
const row = `
<tr>
<td></td>
<td>${tanggalPesan}</td>
<td>${rjData.nurse} (Homecare)</td>
<td>Rawat Jalan</td>
<td>${jadwal}</td>
<td>Rp ${rjData.price.toLocaleString("id-ID")}</td>
<td><span class="status-pending">Dijadwalkan</span></td>
</tr>
`
tbody.insertAdjacentHTML("afterbegin", row)
updateTableNumbers()
rjNext(3)
}
    
      function closeRawatJalan() {
        // Reset form dan kembali ke dashboard utama
        document.getElementById("section-rawat-jalan").classList.add("hidden");
        document.getElementById("section-services").classList.remove("hidden");

        // Reset temporary data
        rjData = { nurse: "", price: 0, time: "", date: "" };
        document.getElementById("rj-date").value = "";
        document
          .querySelectorAll(".time-slot")
          .forEach((b) => b.classList.remove("selected"));
      }
      // Fungsi untuk memindahkan pilihan QRIS/VA khusus Rawat Jalan
      function selectRJPaymentMethod(methodType) {
        document.getElementById("rj-qris-image-area").classList.add("hidden");
        document.getElementById("rj-va-banks-area").classList.add("hidden");
        document.getElementById("rj-va-display-box").classList.add("hidden");

        if (methodType === "qris") {
          document
            .getElementById("rj-qris-image-area")
            .classList.remove("hidden");
          document.getElementById("rj_pay_qris").checked = true;
        } else if (methodType === "va") {
          document
            .getElementById("rj-va-banks-area")
            .classList.remove("hidden");
          document.getElementById("rj_pay_va").checked = true;
        }
      }

      // Fungsi untuk menampilkan nomor VA khusus Rawat Jalan
      function showRJVANumber(bank, number) {
        const vaBox = document.getElementById("rj-va-display-box");
        vaBox.classList.remove("hidden");
        document.getElementById("rj-target-bank").innerText = bank;
        document.getElementById("rj-va-number-text").innerText = number;
      }

      // Update fungsi selectNurse agar label harga di summary ikut berubah
      function selectNurse(name, price) {
        rjData.nurse = name;
        rjData.price = price;
        document.getElementById("rj-display-nurse").innerText = name;
        document.getElementById("rj-total-price-label").innerText =
          "Rp " + price.toLocaleString("id-ID");
        rjNext(1);
      }
    </script>
  </body>
</html>