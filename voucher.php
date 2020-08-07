<?php include("includes/header.php"); ?>
<div class="row justify-content-center mt-5">
    <div class="col-6">  
        <form>
            <label for="exampleInput">Create Voucher</label>
            <div class="input-group mb-3">
                
                <input type="text" class="form-control" placeholder="Enter Amount...." aria-label="Enter Amount...." aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary mb-2">Create ID</button>
                </div>
            </div>
        </form>
        <form>
            <label for="exampleInput">Reedeem Voucher</label>
            <div class="input-group mb-3">
                
                <input type="text" class="form-control" placeholder="Enter Voucher Id...." aria-label="Enter Voucher Id...." aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary mb-2">Redeem</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include("includes/footer.php"); ?>
