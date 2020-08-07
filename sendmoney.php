<?php include("includes/header.php"); ?>
<div class="row justify-content-center mt-5">
    <div class="col-6">
        <form class="reqCreditMoney">
            <div class="form-group ">
                <label for="formGroupExampleInput">Send To</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Enter Name...">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Amount</label>
                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Enter Amount...">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>

<div class="row justify-content-center mt-5">
    <div class="col-6"> 
    <h3>Transfer Requests</h3>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Who</th>
                <th scope="col">Amount</th>
                <th scope="col">Req-Date</th>
                <th scope="col">Responsce</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <th scope="row">1</th>
                <td>From user_A@exapmple.com</td>
                <td>20 Points Requested</td>
                <td>2020-01-23</td>
                <td><button type="button" class="btn btn-dark">Pay</button></td>
                </tr>
                <tr>
                <th scope="row">1</th>
                <td>To user_B@exapmple.com</td>
                <td>15 Points Requested</td>
                <td>2020-01-28</td>
                <td><button type="button" class="btn btn-dark">Pay</button></td>
                </tr>
                <tr>
                <th scope="row">1</th>
                <td>To user_B@exapmple.com</td>
                <td>100 Points Requested</td>
                <td>2020-01-28</td>
                <td><button type="button" class="btn btn-dark">Pay</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div
<?php include("includes/footer.php"); ?>
