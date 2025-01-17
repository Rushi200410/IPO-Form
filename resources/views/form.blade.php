<!doctype html>
<html>
  <head>
    <title>{{$title}}</title>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
      .shadow-input {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  </head>
  <body>
    <br><br>
    <h3 class="py-3" align="center">{{$title}}</h3><br>

    <div class="row ms-5 me-5">
      <div class="col-xxl ms-5 me-5" style="padding-left: 8%; padding-right: 8%;">
        <div class="container-fluid card mb-4 ms-5 me-5">
          <div class="card-body ms-5 me-5">
            <form action="{{$url}}" method="post">
              @csrf

              <div class="row mb-3 justify-content-center">
                <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label class="col-form-label">Name</label>
                    <select class="form-control shadow-input abc2" name="user" id="user_id">
                      <?php foreach($users_data as $id => $name) { ?>
                        <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div><br>

              <div class="row mb-3 justify-content-center">
                <div class="col-md-6 col-sm-12">
                  <div class="form-group">
                    <label class="col-form-label">Form</label>
                    <select class="form-control shadow-input" name="FormNo" id="FormNo">
                      <?php foreach($pdfFileNames as $id => $name) { ?>
                        <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div><br>


              <div class="row mb-3 justify-content-center">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <label class="col-form-label">Lot Size</label>
                      <input type="number" name="lot_size" id="lot_size" class="form-control shadow-input abc" placeholder="LOT size" value="">
                      <label class="col-form-label">Price Of Share</label>
                      <input type="number" name="price" id="price" class="form-control shadow-input abc" placeholder="Price of Shares" value="">
                      <label class="col-form-label">No. of Lot</label>
                      <input type="number" name="no_of_lot" id="no_of_lot" class="form-control shadow-input abc" placeholder="No. of LOTs" value="">
                  </div>
                </div>
              </div>

              <div class="row mb-3 justify-content-center">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                      <h6 style="margin-left: -190px; margin-top: -30px;"> Amount = <span id="product">0</span></h6>
                    </div>
                </div>
              </div>

              <div class="row justify-content-center">
                <div class="col-sm-2 ms-5 mt-4 mb-2">
                  <button type="submit" class="btn btn-primary btn-lg">Submit</button><br>
                  &nbsp;&nbsp;<a href="{{route('UplodePage')}}">Re-Upload</a>
                  {{-- <a href="{{}}" --}}
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>


    <script>
      // Get references to the input fields and the product display element
      const lot_size = document.getElementById('lot_size');
      const price = document.getElementById('price');
      const no_of_lot = document.getElementById('no_of_lot');
      const productDisplay = document.getElementById('product');

      // Function to calculate the product and update the display
      function calculateProduct() {
          const val1 = parseFloat(lot_size.value) || 0; // If empty, default to 0
          const val2 = parseFloat(price.value) || 0; // If empty, default to 0
          const val3 = parseFloat(no_of_lot.value) || 0; // If empty, default to 0
          const product = val1 * val2 * val3;
          productDisplay.textContent = product; // Display the product
      }

      // Add event listeners to both input fields
      lot_size.addEventListener('input', calculateProduct);
      price.addEventListener('input', calculateProduct);
      no_of_lot.addEventListener('input', calculateProduct);
  </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
