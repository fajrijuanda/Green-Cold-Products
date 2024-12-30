<!-- Footer: Start -->
<footer class="landing-footer bg-body footer-text">
  <div class="footer-top position-relative overflow-hidden z-1">
      <img src="{{ asset('assets/img/front-pages/backgrounds/footer-bg-' . $configData['style'] . '.png') }}"
          alt="footer bg" class="footer-bg banner-bg-img z-n1"
          data-app-light-img="front-pages/backgrounds/footer-bg-light.png"
          data-app-dark-img="front-pages/backgrounds/footer-bg-dark.png" />
      <div class="container">
          <div class="row gx-0 gy-6 g-lg-10">
              <div class="col-lg-5">
                  <a href="{{ url('login') }}" class="app-brand-link mb-6">
                      <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                      <span
                          class="app-brand-text demo footer-link fw-bold ms-2 ps-1">Green Cold Products</span>
                  </a>
                  <div class="footer-company-info">
                      <p class="company-name fw-bold" style="color:white;">PT. GREEN COLD</p>
                      <p class="company-address" style="color:white;">
                          Jalan Maligi X Lot V No.8A Karawang International Industrial City, Margakaya, Kec.
                          Telukjambe Bar., Kabupaten Karawang, Jawa Barat, 41361
                      </p>
                      <p class="company-contact" style="color:white;">
                          Phone: (0267) 8459250 <br>Email: marketing@greencold.co.id <br>Website: <a href="http://www.greencold.co.id" target="_blank" style="color:yellow;">www.greencold.co.id</a>
                      </p>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="footer-bottom py-3 py-md-5">
      <div
          class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
          <div class="mb-2 mb-md-0">
              <span class="footer-bottom-text">©
                  <script>
                      document.write(new Date().getFullYear());
                  </script>
              </span>
              <a href="{{ config('variables.creatorUrl') }}" target="_blank"
                  class="fw-medium text-white text-white">{{ config('variables.creatorName') }},</a>
              <span class="footer-bottom-text"> Made with ❤️ for a better web.</span>
          </div>
          <div>
              <a href="{{ config('variables.githubUrl') }}" class="me-3" target="_blank">
                  <img src="{{ asset('assets/img/front-pages/icons/github.svg') }}" alt="github icon" />
              </a>
              <a href="{{ config('variables.facebookUrl') }}" class="me-3" target="_blank">
                  <img src="{{ asset('assets/img/front-pages/icons/facebook.svg') }}" alt="facebook icon" />
              </a>
              <a href="{{ config('variables.twitterUrl') }}" class="me-3" target="_blank">
                  <img src="{{ asset('assets/img/front-pages/icons/twitter.svg') }}" alt="twitter icon" />
              </a>
              <a href="{{ config('variables.instagramUrl') }}" target="_blank">
                  <img src="{{ asset('assets/img/front-pages/icons/instagram.svg') }}" alt="google icon" />
              </a>
          </div>
      </div>
  </div>
</footer>
<!-- Footer: End -->
