<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">

<h1>Insurance Quotation Form</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form  id="quotationForm" method="POST" action="{{ route('api.quotation.calculate') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-3">
    <label for="age">Age:</label>
    <input type="text" id="age" name="age" class="form-control" required>
    <div class="invalid-feedback">
      Please enter the age.
    </div>
  </div>
  <div class="mb-3">
    <label for="currency_id">Currency:</label>
    <select id="currency_id" name="currency_id" class="form-select" required>
      <option value="">Select Currency</option>
      <option value="EUR">EUR</option>
      <option value="GBP">GBP</option>
      <option value="USD">USD</option>
    </select>
    <div class="invalid-feedback">
      Please select a currency.
    </div>
  </div>
  <div class="mb-3">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" class="form-control" required>
    <div class="invalid-feedback">
      Please enter a valid start date in the format yyyy-mm-dd.
    </div>
  </div>
  <div class="mb-3">
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" class="form-control" required>
    <div class="invalid-feedback">
      Please enter a valid end date in the format yyyy-mm-dd.
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<div id="responseContainer"></div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


<script>
  const responseContainer = document.getElementById('responseContainer');

  document.getElementById('quotationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    axios.post(this.action, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    .then(function(response) {
      const responseData = response.data;
      responseContainer.innerText = JSON.stringify(responseData);
    })
    .catch(function(error) {
      console.error(error.response.data);
      responseContainer.innerText = JSON.stringify(error.response.data);
    });
  });
</script>