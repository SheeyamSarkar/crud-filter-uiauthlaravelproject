<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <title>Products</title>
    <style>
        .mr-2{
            margin-right: 2px !important;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <h2 class="my-3">Products</h2>
                <a href="" class="btn btn-success my-3" data-bs-toggle="modal"
                    data-bs-target="#productAddModal">Add Product</a>
                <br>
                {{-- search --}}

                <div class="table-data" id='ptable'>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Title</th>
                                <th scope="col">Subcategory</th>
                                <th scope="col">Price</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($products))
                                @foreach ($products as $product)
                                    <tr class="pitem">
                                        <td><img class="img-fluid" src="{{ asset('backend/' . $product->thumbnail) }}"
                                                style="width: 60px; height: 55px;"></td>
                                        <td>{{ $product->title }}</td>
                                        <td>{{ $product->getSubCategory ? $product->getSubCategory->title : 'N/A' }}
                                        </td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            {{-- <a href="" type= 'button' class="btn btn-success" onclick='editProduct({{ $product->id}})'><i class="lar la-edit"></i></a> --}}
                                            {{-- <a href="" class="btn btn-danger"><i class="lar la-trash-alt"></i></a> --}}
                                            <button class="btn btn-danger"
                                                onclick='deleteProduct({{ $product->id }})'><i
                                                    class="lar la-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
        <h4>Filter Options</h4>
        <div class="d-flex justify-content-start">
            <div class="dropdown mr-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Filter BY Category
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    @foreach ($categories as $cat)
                        <li><a class="dropdown-item"
                                onclick="filterCategory({{ $cat->id }})">{{ $cat->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="dropdown mr-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Filter BY Subcategory
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    @foreach ($subcategories as $subcat)
                        <li><a class="dropdown-item"
                                onclick="filterSubCategory({{ $subcat->id }})">{{ $subcat->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="search-bar mr-2">
                <form id="searchProduct"> @csrf
                    <div class="input-group">
                        <input type="text" name="product_title" id="search_product" class="form-control"
                            placeholder="Search">
                        <button class="input-group-text" type="submit"><i class="lab la-searchengin"></i></button>
                    </div>
                </form>
            </div>

            <div  class="search">
                <form id="priceFilter">
                    @csrf
                    <input type="text" name="min_price" id="min_price" class="form-control mr-1"
                    placeholder="min">
                    <input type="text" name="max_price" id="max_price" class="form-control"
                    placeholder="max">
                    <button type="submit" class="btn btn-success">Filter</button>
                </form>
               
            </div>
        </div>
       
        <h4>All Items</h4>
        <div id="items" class="d-flex">
            @if (!empty($products))
                @foreach ($products as $product)
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset('backend/' . $product->thumbnail) }}" class="card-img-top"
                            alt="{{ $product->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>


    <!-- Add Modal -->
    <div class="modal fade" id="productAddModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <form action="" id="productAddForm" enctype="multipart/form-data">@csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="errMsgContainer"></div>
                        <div class="form-group">
                            <label for="subcategory">Select Subcategory</label>
                            <select class="form-select" type="text" name="subcategory_id"
                                aria-label="Default select example">
                                <option value="deafult" hidden>Select Subcategory</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" class="form-control" name="price">
                        </div>
                        <div class="form-group">
                            <label for="" class='input_field_label'>Thumbnail</label>
                            <input type="file" name="thumbnail" id="fileUpload1" class="fileUpload image"
                                multiple />
                            <div class="result">
                                <div class="field_imglist"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        var path = "{{ url('/') }}";
        var config = {
            routes: {
                add: "{!! route('product.store') !!}",
                delete: "{!! route('product.delete') !!}",
                search: "{!! route('product.list') !!}",
                filtercat: "{!! route('product.filtercat') !!}",
                filtersubcat: "{!! route('subcategory.product') !!}",
                filtersearch: "{!! route('product.searchlist') !!}",
                filterprice: "{!! route('product.price') !!}",

            }
        };

        $(document).ready(function() {
            CKEDITOR.replace('description');
        });


        $(document).ready(function() {
            $(document).off('submit', '#productAddForm');
            $(document).on('submit', '#productAddForm', function(event) {
                event.preventDefault();
                $.ajax({
                    url: config.routes.add,
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",

                    success: function(response) {
                        if (response.success == true) {
                            $('#productAddForm').trigger('reset');
                            $('#productAddModal').modal('hide');
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("Product Added")

                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }

                    },
                    error: function(err) {
                        let error = err.responseJSON;
                        $.each(error.errors, function(index, value) {
                            $('.errMsgContainer').append('<span class="text-danger">' +
                                value + '</span>' + '<br>');
                        });
                    }
                });

            });
        });

        function deleteProduct(id) {
            if (confirm('Are You Sure To Delete ??')) {

                $.ajax({
                    type: "POST",
                    url: config.routes.delete,
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'JSON',
                    success: function(response) {

                        if (response.success === true) {
                            $('.table').load(location.href + ' .table');
                            Command: toastr["warning"]("Product Deleted")
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }
                    }
                });

            }
        }

        //filter category
        function filterCategory(id) {
            console.log(id)
            $.ajax({
                type: "POST",
                url: config.routes.filtercat,
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'JSON',
                success: function(response) {
                    console.log("res " + response.data);
                    $('#items').empty();
                    $.each(response.data, function(key, value) {
                        $('#items').append(`
                        <div class="card" style="width: 18rem; margin-right:5px;">
                            <img src="{{ asset('backend/${value.thumbnail}') }}" class="card-img-top" alt="${value.title}">
                            <div class="card-body">
                                <h5 class="card-title">${value.title}</h5>
                                <p class="card-text">${value.description}</p>
                                <p class="card-text">${value.price}</p>
                            </div>
                        </div> `)
                    })

                }
            })
        }

        function filterSubCategory(id) {
            console.log(id)
            $.ajax({
                type: "POST",
                url: config.routes.filtersubcat,
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'JSON',
                success: function(response) {
                    console.log("res " + response.data);
                    $('#items').empty();
                    $.each(response.data, function(key, value) {
                        $('#items').append(`
                        <div class="card" style="width: 18rem; margin-right:5px;">
                            <img src="{{ asset('backend/${value.thumbnail}') }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">${value.title}</h5>
                                <p class="card-text">${value.description}</p>
                                <p class="card-text">${value.price}</p>
                            </div>
                        </div> `)
                    })

                }
            })
        }

        $(document).on('submit', '#searchProduct', function(event) {
            event.preventDefault();
            var product_title = document.getElementById('search_product').value;

            $.ajax({
                url: config.routes.filtersearch,
                method: "POST",
                data: {
                    product_title: product_title,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    $('#items').empty();
                    $.each(response.data, function(key, value) {
                        $('#items').append(`
                        <div class="card" style="width: 18rem; margin-right:5px;">
                            <img src="{{ asset('backend/${value.thumbnail}') }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">${value.title}</h5>
                                <p class="card-text">${value.description}</p>
                                <p class="card-text">${value.price}</p>
                            </div>
                        </div> `)
                    })


                }, //success end
            });
        });
        $(document).on('submit', '#priceFilter', function(event) {
            event.preventDefault();
            var min = document.getElementById('min_price').value;
            var max = document.getElementById('max_price').value;

            $.ajax({
                url: config.routes.filterprice,
                method: "POST",
                data: {
                    min: min,
                    max: max,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    $('#items').empty();
                    $.each(response.data, function(key, value) {
                        $('#items').append(`
                        <div class="card" style="width: 18rem; margin-right:5px;">
                            <img src="{{ asset('backend/${value.thumbnail}') }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">${value.title}</h5>
                                <p class="card-text">${value.description}</p>
                                <p class="card-text">${value.price}</p>
                            </div>
                        </div> `)
                    })


                }, //success end
            });
        });

        //Search
        var availableTags = [];
        $.ajax({
            method: "GET",
            url: config.routes.search,
            success: function(response) {
                // console.log(response)
                startAutoComplete(response);
            }
        })

        function startAutoComplete(availableTags) {
            $("#search_product").autocomplete({
                source: availableTags
            });
        }
    </script>

</body>

</html>
