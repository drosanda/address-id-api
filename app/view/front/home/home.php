<div class="section">
  <div class="container">

    <div class="">
      <div class="column">
        <div class="content">
          <h1 id="home" class="is-size-1">Indonesian Address Provider <span>API</span></h1>
          <p>
            Indonesia has 5 levels of address types starting from the province, district or city, sub-district and finally the kelurahan (sub from sub-district).
          </p>
          <p>
            This address types, sometimes confusing peoples if they are less aware of the surrounding area.
          </p>
          <p>
            So, with from that problem we built solution for providing address to get matched content or nearly closed to exact address.
          </p>
          <hr>
          <h2 id="keyfeature">Key Features</h2>
          <p>
            This API must be able to display 5 types of addresses based on the hierarchy plus the zipcode (postal code).
          </p>
          <hr>
          <p>
            <strong>Lightweight</strong>. This API was built from Seme Framework v4.0.0 (PHP MVC framework) that blazingly fast with simple codes.
          </p>
          <p>
            <strong>RDBMS</strong>. You can played with the data sources, because its use common DB that used with PHP, like MySQL or MariaDB.
          </p>
          <p>
            <strong>JSON</strong>. The API output has own standard result in json format that can be implemented to any applications.
          </p>
          <hr>

          <h2 id="license">License</h2>
          <p>
            This API and Seme Framework are licensed under MIT.
          </p>
          <hr>

          <h2 id="qna">Question and Answer</h2>
          <p>
            Feel free to ask me on my <a href="https://instagram.com/drosanda/" target="_blank">instagram</a>, <a href="https://facebook.com/drs11/" target="_blank">facebook</a>, or open an issue on <a href="https://github.com/drosanda/seme-indonesian-address-provider-api/issues" target="_blank">github</a>.
          </p>
          <hr>

          <h2 id="resources">Resources</h2>
          <p>
            This API and pages source codes are available on <a href="" target="_blank">github</a>.
          </p>
          <hr>
          <h2 id="gs">Getting Started</h2>
          <p>This API is can be used for free but with no warranty. First thing first the base url of API is <code>https://alamat.thecloudalert.com/api/</code>.</p>
          <p>The API endpoint should follow the base url before consumed.</p>
          <h2 id="gs_provinsi">Get Provinces</h2>
          <p>API endpoint for generating the list of provinces in Indonesia.</p>
          <p><code>https://alamat.thecloudalert.com/api/provinsi/get/</code></p>
          <p>And the example result is</p>
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
            <h2 id="gs_kabkota">Get Cities</h2>
            <p>API endpoint for getting the list of cities in Indonesia by province ID.</p>
            <p><code>https://alamat.thecloudalert.com/api/kabkota/get/?d_provinsi_id=[ID_FROM_PROVINCE_API]</code></p>
            <p>And the example result is</p>
            <p><code>https://alamat.thecloudalert.com/api/kabkota/get/?d_provinsi_id=1</code></p>
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
            <h2 id="gs_kecamatan">Get Districts</h2>
            <p>API endpoint for getting the list of districts in Indonesia by cities ID.</p>
            <p><code>https://alamat.thecloudalert.com/api/kecamatan/get/?d_kabkota_id=[ID_FROM_CITIES_API]</code></p>
            <p>And the example result is</p>
            <p><code>https://alamat.thecloudalert.com/api/kecamatan/get/?d_kabkota_id=1</code></p>
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
            <h2 id=gs_kelurahan>Get Sub Districts</h2>
            <p>API endpoint for getting the list of sub districts in Indonesia by districts ID.</p>
            <p><code>https://alamat.thecloudalert.com/api/kelurahan/get/?d_kecamatan_id=[ID_FROM_DISTRICT_API]</code></p>
            <p>And the example result is</p>
            <p><code>https://alamat.thecloudalert.com/api/kelurahan/get/?d_kecamatan_id=1</code></p>
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
            <h2 id="gs_kodepos">Get Zip Codes</h2>
            <p>API endpoint for getting the list of Zip Codes in Indonesia by cities ID and districts ID.</p>
            <p><code>https://alamat.thecloudalert.com/api/kodepos/get/?d_kabkota_id=[ID_FROM_CITIES_API]&amp;d_kecamatan_id=[ID_FROM_DISTRICT_API]</code></p>
            <p>And the example result is</p>
            <p><code>https://alamat.thecloudalert.com/api/kodepos/get/?d_kabkota_id=1&amp;d_kecamatan_id=1</code></p>
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
        </div>
      </div>
    </div>

  </div>
</div>
