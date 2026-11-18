<div class="card p-4 shadow-sm mb-4">
    <h4 class="mb-3">Auto Loan Calculator</h4>
    <p>You can calculate monthly loan amount using this calculator</p>

    <form id="loan-calculator-form">
        <div class="mb-3">
            <label for="loanAmount" class="form-label">Loan Amount *</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" id="loanAmount" class="form-control" placeholder="Amount" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="interestRate" class="form-label">Interest Rate *</label>
            <div class="input-group">
                <span class="input-group-text">%</span>
                <input type="number" id="interestRate" class="form-control" placeholder="Rate" step="0.01" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="loanTerm" class="form-label">Loan Term in Years *</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="number" id="loanTerm" class="form-control" placeholder="Year" required>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Loan Calculate</button>
            <button type="reset" class="btn btn-light" onclick="resetCalculator()">Reset Now</button>
        </div>
    </form>

    <hr class="my-4">

    <div class="fs-5">
        <strong>Monthly Payment:</strong> <span id="monthlyPayment">$0.00</span>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('loan-calculator-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const amount = parseFloat(document.getElementById('loanAmount').value);
        const interestRate = parseFloat(document.getElementById('interestRate').value);
        const termYears = parseInt(document.getElementById('loanTerm').value);

        const n = termYears * 12; 
        const r = interestRate / 100 / 12; 

        let monthlyPayment = 0;

        if (r === 0) {
            monthlyPayment = amount / n;
        } else {
            monthlyPayment = (amount * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        }

        if (!isNaN(monthlyPayment)) {
            document.getElementById('monthlyPayment').innerText = `$${monthlyPayment.toFixed(2)}`;
        } else {
            document.getElementById('monthlyPayment').innerText = "$0.00";
        }
    });

    function resetCalculator() {
        document.getElementById('monthlyPayment').innerText = "$0.00";
    }
</script>
@endpush
