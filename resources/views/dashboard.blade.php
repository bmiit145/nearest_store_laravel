<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Find Nearest Store</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{--    Links for jquery--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{--    Links for bootstrap 5--}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert JavaScript CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>
<body>
<div id="app">
    {{--        navbar--}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand mx-3" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                {{--                    <li class="nav-item active">--}}
                {{--                        <a class="nav-link" href="#"></a>--}}
                {{--                    </li>--}}
                {{--                    <li class="nav-item">--}}
                {{--                        <a class="nav-link" href="#">Features</a>--}}
                {{--                    </li>--}}
                {{--                    <li class="nav-item">--}}
                {{--                        <a class="nav-link" href="#">Pricing</a>--}}
                {{--                    </li>--}}
                {{--                    <li class="nav-item">--}}
                {{--                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>--}}
                {{--                    </li>--}}
            </ul>
        </div>
    </nav>
    {{--  find nearest store from latitute and longitute  --}}
    <div class="container">
        <div class="find-nearest-div m-2">
            <h1 class="text-center">Find Nearest Store</h1>
            <hr>
            <div class="col-6">
                <form class="form" action="" method="post" id="find_store_form">
                    <div class="form-group">
                        <label for="latitute">Latitute</label>
                        <input type="text" class="form-control mb-3 mt-1" id="latitute" name="latitute"
                               placeholder="Enter latitute">
                    </div>
                    <div class="form-group">
                        <label for="longitute">Longitute</label>
                        <input type="text" class="form-control mb-3 mt-1" id="longitute" name="longitute"
                               placeholder="Enter longitute">
                    </div>
                    <button type="submit" class="btn btn-primary">Find Nearest Store</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function () {
        $('#find_store_form').on('submit', function (e) {
            e.preventDefault();
            let latitute = $('#latitute').val();
            let longitute = $('#longitute').val();
            $.ajax({
                url: "{{ route('find_nearest_store') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    latitute: latitute,
                    longitute: longitute
                },
                success: function (response) {
                    console.log(response);
                    if (response.status === 200) {
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 422) {
                        // Handle validation errors
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '';

                        // Iterate through the validation errors and concatenate them
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessage += `${errors[key][0]}<br>`;
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error!',
                            html: errorMessage,
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.error,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An unexpected error occurred.',
                        });
                    }
                }
            });
        });
    });
</script>
</body>
</html>
