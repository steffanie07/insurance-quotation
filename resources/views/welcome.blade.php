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

<div id="registrationSection">
    <h2>Step 1: Registration</h2>
    <form id="registrationForm" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
            <div class="invalid-feedback">
                Please enter your name.
            </div>
        </div>
        <div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
            <div class="invalid-feedback">
                Please enter a valid email address.
            </div>
        </div>
        <div class="mb-3">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <div class="invalid-feedback">
                Please enter a password.
            </div>
        </div>
        <div class="mb-3">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            <div class="invalid-feedback">
                Please confirm your password.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<div id="quotationSection" style="display: none;">
    <h2>Step 2: Quotation</h2>
    <form id="quotationForm" class="needs-validation" novalidate>
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
                Please enter a valid start date 
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
</div>

<div id="responseContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const responseContainer = document.getElementById('responseContainer');

    document.getElementById('registrationForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value
        };

        axios.post('api/register', formData)
            .then(function (response) {
                const token = response.data.access_token;
                showQuotationSection(token);
            })
            .catch(function (error) {
                console.error(error.response.data);
                responseContainer.innerText = JSON.stringify(error.response.data);
            });
    });

    document.getElementById('quotationForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = {
            age: document.getElementById('age').value,
            currency_id: document.getElementById('currency_id').value,
            start_date: document.getElementById('start_date').value,
            end_date: document.getElementById('end_date').value
        };

        const token = localStorage.getItem('jwtToken');

        axios.post('api/quotation', formData, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'credentials': 'same-origin'
                }
            })
            .then(function (response) {

                const responseData = response.data;
                responseContainer.innerText = JSON.stringify(responseData);
            })
            .catch(function (error) {
                console.error(error.response.data);
                responseContainer.innerText = JSON.stringify(error.response.data);
            });
    });

    function showQuotationSection(token) {
        localStorage.setItem('jwtToken', token);
        document.getElementById('registrationSection').style.display = 'none';
        document.getElementById('quotationSection').style.display = 'block';
    }
</script>
