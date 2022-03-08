<script type="application/ld+json">
  [
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "item": {
            "@id": "<?=base_url()?>",
            "name": "Seme Indonesia Address API Documentation"
          }
        },
        {
          "@type": "ListItem",
          "position": 2,
          "item": {
            "@id": "<?=base_url('id')?>",
            "name": "<?=$this->getTitle()?>"
          }
        }
      ]
    },
    {
      "@context": "http://schema.org",
      "@type": "NewsArticle",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?=base_url()?>"
      },
      "headline": "<?=$this->getTitle()?>",
      "dateCreated": "2015-02-05T08:00:00+08:00",
      "datePublished": "2015-02-05T08:00:00+08:00",
      "dateModified": "2022-03-08T20:10:00+07:00",
      "author": {
        "@type": "Person",
        "gender": "Male",
        "name": "Daeng Rosanda, S.Kom",
        "alternateName": "Daeng Rosanda",
        "jobTitle": "Founder",
        "worksFor": {
          "@type": "Organization",
          "name": "Cipta Esensi Merenah",
          "email": "hi@cenah.co.id"
        }
      },
      "publisher": {
        "@type": "Organization",
        "name": "Cipta Esensi Merenah",
        "description": "Cipta Esensi Merenah (Cenah) is software house company focused on developing web-based application from Bandung, Indonesia.",
        "logo": {
          "@type": "ImageObject",
          "name": "logo Cipta Esensi Merenah",
          "url": "https://cdn.cenah.co.id/_nuxt/img/logo-wide.5420183.png",
          "width": "256px",
          "height": "62px"
        }
      },
      "description": "<?=$this->getDescription()?>"
    }
  ]
</script>

<div class="section">
  <div class="container">

    <div class="">
      <div class="column">
        <div class="content">
          <h1 id="home" class="is-size-1">Dokumentasi <span>API</span> Alamat di Indonesia</h1>
          <p>
            Indonesia memiliki 5 tingkat daerah pengalamatan yaitu provinsi, kabupaten atau kota, kecamatan, dan desa atau kelurahan.
          </p>
          <p>
            Saking banyaknya jenis alamat ini, bisa jadi membingungkan untuk beberapa pendatang yang ingin menentukan alamatnya di Indonesia.
          </p>
          <p>
            Jadi, tujuan API ini dibuat adalah untuk melakukan proses pencarian alamat dengan keyword tertentu sehingga bisa muncul secara lengkap mulai dari provinsi sampai dengan desa / kelurahan.
          </p>
          <hr>
          <h2 id="keyfeature">Fitur</h2>
          <p>
            API ini memungkinkan untuk menampilkan data alamat hingga kodepos-nya, adapun keunggulan lainnya yaitu
          </p>
          <hr>
          <p>
            <strong>Ringan</strong>. API ini dibuat dengan menggunakan Seme Framework yang ringan dan cepat.
          </p>
          <p>
            <strong>RDBMS</strong>. Database yang telah dioptimasi dengan menggunakan indexing dan fitur cache untuk MariaDB.
          </p>
          <p>
            <strong>JSON</strong>. Output API berupa JSON yang simple dan memudahkan untuk digunakan.
          </p>
          <hr>

          <h2 id="license">Lisensi</h2>
          <p>
            Baik API, data, maupun kode yang ada dihalaman ini mematuhi lisensi MIT.
          </p>
          <hr>

          <h2 id="qna">Tanya / Jawab</h2>
          <p>
            Jika ada pertanyaan dan jawaban, bisa langsung hub saya via <a href="https://instagram.com/drosanda/" target="_blank">instagram</a>, <a href="https://facebook.com/drs11/" target="_blank">facebook</a>, or atau <a href="https://github.com/drosanda/seme-indonesian-address-provider-api/issues" target="_blank">github</a>.
          </p>
          <hr>

          <h2 id="resources">Kode Sumber</h2>
          <p>
            Anda bebas untuk mengembangkan versi anda sendiri dengan cara fork reponya <a href="https://github.com/drosanda/address-id-api" target="_blank">di github</a>.
          </p>

          <hr>
          <h2 id="gs">Panduan Pengguna</h2>
          <p>API ini dapat digunakan secara <b>Gratis</b> tapi tidak ada jaminan bahwa layanan ini akan aktif terus menerus. Pertama-tama URL dasar (API Endpoint)-nya adalah <code>https://alamat.thecloudalert.com/api/</code>.</p>
          <p>Semua proses pengambilan data ada setelah Endpoint tersebut.</p>

          <h2 id="gs_provinsi">Mengambil data Provinsi</h2>
          <p>Untuk menampilkan list data provinsi, cukup dengan mengakses endpoint berikut ini.</p>
          <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/provinsi/get/</code></p>
          <p>Dan berikut ini adalah hasilnya:</p>
          <pre>{
  "status": 200,
  "message": "Berhasil",
  "result": [
    {
      "id": "1",
      "text": "Bali"
    },
    {
      "id": "2",
      "text": "Bangka Belitung"
    }
  ]
}
            </pre>
            <h2 id="gs_kabkota">Mendapatkan list Kabupaten / Kota</h2>
            <p>Endpoint API untuk mendapatkan list kabupaten / kota.</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kabkota/get/?d_provinsi_id=[ID_FROM_PROVINCE_API]</code></p>
            <p>Berikut ini adalah contoh hasilnya:</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kabkota/get/?d_provinsi_id=1</code></p>
            <pre>{
  "status": 200,
  "message": "Berhasil",
  "result": [
    {
      "id": "1",
      "text": "Kabupaten Badung"
    },
    {
      "id": "2",
      "text": "Kabupaten Bangli"
    }
  ]
}
            </pre>
            <h2 id="gs_kecamatan">Mendapatkan list Kecamatan</h2>
            <p>Endpoint API untuk mendapatkan list kecamatan.</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kecamatan/get/?d_kabkota_id=[ID_FROM_CITIES_API]</code></p>
            <p>Berikut ini adalah contoh hasilnya:</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kecamatan/get/?d_kabkota_id=1</code></p>
            <pre>{
  "status": 200,
  "message": "Berhasil",
  "result": [
    {
      "id": "1",
      "text": "Abiansemal"
    },
    {
      "id": "2",
      "text": "Kuta"
    }
  ]
}
            </pre>
            <h2 id=gs_kelurahan>Mendapatkan list Desa / Kelurahan</h2>
            <p>Endpoint API untuk mendapatkan list kecamatan.</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kelurahan/get/?d_kecamatan_id=[ID_FROM_DISTRICT_API]</code></p>
            <p>Berikut ini adalah contoh hasilnya:</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kelurahan/get/?d_kecamatan_id=1</code></p>
            <pre>{
  "status": 200,
  "message": "Berhasil",
  "result": [
    {
      "id": "17",
      "text": "Abiansemal"
    },
    {
      "id": "18",
      "text": "Angantaka"
    }
  ]
}
</pre>
            <h2 id="gs_kodepos">Mendapatkan Kodepos</h2>
            <p>Endpoint API untuk mendapatkan list kodepos.</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kodepos/get/?d_kabkota_id=[ID_FROM_CITIES_API]&amp;d_kecamatan_id=[ID_FROM_DISTRICT_API]</code></p>
            <p>Berikut ini adalah contoh hasilnya:</p>
            <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/kodepos/get/?d_kabkota_id=1&amp;d_kecamatan_id=1</code></p>
            <pre>{
  "status": 200,
  "message": "Berhasil",
  "result": [
    {
      "id": "3719",
      "text": "29871"
    },
    {
      "id": "3720",
      "text": "29872"
    }
  ]
}
          </pre>
          <h2 id="gs_kodepos">Filter / Pencarian Alamat</h2>
          <p>Endpoint API untuk mencari alamat dengan <b>Keyword</b> tertentu.</p>
          <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/cari/index/?keyword=[Keyword]</code></p>
          <p>Berikut ini adalah contoh hasilnya:</p>
          <p class="endpoint"><b>GET</b> <code>https://alamat.thecloudalert.com/api/cari/index/?keyword=Soreang</code></p>
          <pre>{
  "status": 200,
  "message": "Berhasil",
  "result": [
      "negara": "Indonesia",
      "provinsi": "Jawa Barat",
      "kabkota": "Kabupaten Bandung",
      "kecamatan": "Soreang",
      "desakel": "Bukit Harapan"
    },
    ...
  ]
}
          </pre>

        </div>
      </div>
    </div>

  </div>
</div>
