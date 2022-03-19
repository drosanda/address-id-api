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
            "name": "<?=$this->main_title?>"
          }
        },
        {
          "@type": "ListItem",
          "position": 2,
          "item": {
            "@id": "<?=base_url('id')?>",
            "name": "<?=$this->main_title_id?>"
          }
        },
        {
          "@type": "ListItem",
          "position": 3,
          "item": {
            "@id": "<?=base_url('id/fitur')?>",
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
      "dateModified": "2022-03-19T12:10:00+07:00",
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

          <h1 id="keyfeature">Fitur</h1>
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
            <strong>JSON</strong>. Output API berupa JSON yang simpel dan memudahkan untuk digunakan.
          </p>

        </div>
      </div>
    </div>

  </div>
</div>
