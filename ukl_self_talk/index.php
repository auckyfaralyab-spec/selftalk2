<?php
include 'includes/koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SelfTalk - SMK Telkom Sidoarjo</title>
    
    <link rel="stylesheet" href="asets/css/index.css">
    <style>

        /* css tmbahan untuk kartu */
        .kartu1, .kartu2, .kartu3 {
            transition: all 0.4s ease;
            border: 1px solid transparent;
        }
        
    </style>
</head>
<body>

    <section class="halaman-hero">
        <div class="label">Media Internal SMK Telkom Sidoarjo</div>
        <h1 class="judul">
            Suaramu <span class="teks-merah">Berharga</span>,<br>
            Mentalmu <span class="teks-merah">Utama</span>.
        </h1>
        <p class="sub-judul">
            SelfTalk hadir sebagai ruang aman bagi siswa SMK Telkom Sidoarjo untuk berbagi cerita, menjaga kesehatan mental, dan melawan bullying bersama-sama.
        </p>
        <a href="pilihan.php" class="tombol-masuk">Masuk</a>
    </section>

    <section class="halaman-fitur">
        <div class="wadah-kartu"> 
            
            <div class="kartu1">
                <div class="kartu1-fitur">
                    <div class="ikon-kartu"><img src="asets/img/profil.png" alt="Anonim & Rahasia"></div>
                    <h3>Anonim & Rahasia</h3>
                    <p>Ceritakan masalahmu di sekolah atau media sosial tanpa identitas. Kami menjamin privasimu sepenuhnya</p>
                </div>
            </div> 
            
            <div class="kartu2">
                <div class="kartu2-fitur">
                    <div class="ikon-kartu"><img src="asets/img/lapor.png" alt="Lapor Guru BK"></div>
                    <h3>Lapor Guru BK</h3>
                    <p>Punya masalah bullying? Lapor secara privat. Laporanmu akan langsung ditinjau oleh Guru BK pilihanmu.</p>
                </div>
            </div> 
            
            <div class="kartu3">
                <div class="kartu3-fitur">
                    <div class="ikon-kartu"><img src="asets/img/teacher 2.png" alt=></div>
                    <h3>Dukungan Mental</h3>
                    <p>Gunakan fitur check-in emosional untuk memantau kesehatan mentalmu selama masa pembelajaran</p>
                </div>
            </div>

        </div> 
    </section>

    <section class="halaman-kutipan">
        <div class="kotak-kutipan">
            <p class="teks-kutipan">"Menciptakan lingkungan sekolah yang nyaman dan bebas perundungan adalah tanggung jawab kita bersama."</p>
            <p class="penulis-kutipan">- Tim Bimbingan Konseling SMK Telkom Sidoarjo</p>
        </div>
    </section>

    
    <footer class="isifooter">
        <div class="konten-kaki">
            
            <div class="kolom-kaki">
                <div class="brand-kaki">
                
                    <span class="nama-brand">SelfTalk</span>
                </div>
            </div> 


            <div class="kolom-kaki">
                <h4 class="judul-kolom">Selftalk</h4>
                <ul class="daftar-tautan">
                    <li><a href="edukasi.php">Edukasi Mental</a></li>
                    <li><a href="konseling_laporan.php">Konseling & Laporan</a></li>
                    <li><a href="berbagi_cerita.php">Berbagi Cerita</a></li>
                </ul>
            </div>

            <div class="kolom-kaki">
                <h4 class="judul-kolom">SEKOLAH</h4>
                <p class="info-sekolah">Jl. Raya Pecantingan Sekardangan, Kabupaten Sidoarjo, Jawa Timur</p>
                <div class="kontak-sekolah">
                    <p>info@skomda.sch.id</p>
                    <p>031-8012627</p>
                </div>
            </div>

            <div class="kolom-kaki">
                <h4 class="judul-kolom">TAUTAN</h4>
                <div class="sosial-media">
                    <span><img src="asets/img/ig.png" alt="Instagram"></span>
                    <span><img src="asets/img/yt.png" alt="YouTube"></span>
                    <span><img src="asets/img/bumi.png" alt="Website"></span>
                </div>
            </div>

        </div>
    </footer>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
           
            const kartu1 = document.querySelector('.kartu1');
            const kartu2 = document.querySelector('.kartu2');
            const kartu3 = document.querySelector('.kartu3');
            const semuaKartu = [kartu1, kartu2, kartu3];

            p)
            semuaKartu.forEach((kartu, index) => {
                if(kartu) {
                    kartu.style.opacity = "0";
                    kartu.style.transform = "translateY(40px)";
                   
                    kartu.style.transition = `all 0.8s cubic-bezier(0.25, 1, 0.5, 1) ${index * 0.2}s`;
                }
            });

            
            const pemicuScroll = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = "1";
                        entry.target.style.transform = "translateY(0)";
                    }
                });
            }, { threshold: 0.1 });

            semuaKartu.forEach(kartu => {
                if(kartu) pemicuScroll.observe(kartu);
            });

            
            semuaKartu.forEach(kartu => {
                if(!kartu) return;

                kartu.addEventListener('mouseenter', () => {
                    kartu.style.transform = "translateY(-10px) scale(1.02)";
                    kartu.style.boxShadow = "0 20px 40px rgba(225, 29, 72, 0.12)";
                    kartu.style.borderColor = "#FFE4E6";
                });

                kartu.addEventListener('mouseleave', () => {
                    kartu.style.transform = "translateY(0) scale(1)";
                    kartu.style.boxShadow = "0 30px 3px rgba(238, 17, 17, 0.03)";
                    kartu.style.borderColor = "transparent";
                });
            });
        });
    </script>
</body>
</html>