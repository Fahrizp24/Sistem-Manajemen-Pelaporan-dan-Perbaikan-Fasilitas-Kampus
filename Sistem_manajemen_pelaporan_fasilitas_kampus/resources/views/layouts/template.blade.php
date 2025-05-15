<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title') | Mantis Bootstrap 5 Admin Template</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('mantis/dist/assets/images/favicon.svg') }}" type="image/x-icon">
  <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="{{ asset('mantis/dist/assets/fonts/tabler-icons.min.css') }}">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="{{ asset('mantis/dist/assets/fonts/feather.css') }}">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="{{ asset('mantis/dist/assets/fonts/fontawesome.css') }}">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="{{ asset('mantis/dist/assets/fonts/material.css') }}">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('mantis/dist/assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('mantis/dist/assets/css/style-preset.css') }}">
  
  @stack('css')
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  @include('layouts.sidebar')
  @include('layouts.header')

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      @include('layouts.breadcrumb')
      
      <!-- [ Main Content ] start -->
      @yield('content')
      <!-- [ Main Content ] end -->
    </div>
  </div>
  <!-- [ Main Content ] end -->

  @include('layouts.footer')

  <!-- Required Js -->
  <script src="{{ asset('mantis/dist/assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('mantis/dist/assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('mantis/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('mantis/dist/assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('mantis/dist/assets/js/fonts/custom-ant-icon.js') }}"></script>
  <script src="{{ asset('mantis/dist/assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('mantis/dist/assets/js/plugins/feather.min.js') }}"></script>

  <script>
    layout_change('light');
    change_box_container('false');
    layout_rtl_change('false');
    preset_change('preset-1');
    font_change('Public-Sans');
  </script>

  @stack('js')
</body>
</html>