<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <style type="text/css">
        .box{
         width:600px;
         margin:0 auto;
         border:1px solid #ccc;
        }
       </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 8 Import Export Excel & CSV File - TechvBlogs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 text-center">
        <h2 class="mb-4">
            Laravel 8 Import Export Excel & CSV File - <a href="https://www.youtube.com/" target="_blank">Youtube</a>
        </h2>
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-4">
                <div class="custom-file text-left">
                    <input type="file" name="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <button class="btn btn-primary">Import Stock</button>
            <a class="btn btn-success" href="{{ route('export-users') }}">Export Stock</a>
            <a class="btn btn-warning" href="{{ route('replace-stock') }}">Replace Stock</a>
            <a class="btn btn-danger" href="{{ route('empty-table') }}">Empty Table</a>
        </form>
    </div>
</body>

<body>
    <br />
    <div class="container">
     {{-- <h3 align="center">Export Data to Excel in Laravel using Maatwebsite</h3><br /> --}}
     <br />
     <div class="table-responsive">
      <table class="table table-striped table-bordered">
       <tr>
        <td align="center"><h4>SKU</h3></td>
        <td align="center"><h4>Stock</h4></td>
        
       </tr>
       @foreach($replace_stock as $product)
       <tr>
        <td align="center">{{ $product->sku }}</td>
        <td align="center">{{ $product->count }}</td>
       </tr>
       @endforeach
      </table>
     </div>
     
    </div>
   </body>

</html>