<div class="items-wrapper">
    
    <div class="items-table-header">
        <div class="item-name">
            Item
        </div>
        <div class="item-description">
            Description
        </div>
        <div class="item-unit-price">
            Unit Price
        </div>
        <div class="item-quantity">
            Quantity
        </div>
        <div class="item-amount">
            Amount
        </div>
    </div>
    
    <div class="items-table-row" id="items-row" item-list>
        <div class="item-name">
            <select class="form-control" name="item-name[]">
                <option></option>
                <option>Days</option>
                <option>Hours</option>
                <option>Product</option>
                <option>Service</option>
                <option>Expense</option>
                <option>Discount</option>
            </select>
        </div>
        <div class="item-description">
            <div class="form-group">
                <textarea class="form-control" rows="1" name="item-description[]"></textarea>
            </div>
        </div>
        <div class="item-unit-price">
            <div class="form-group">
                <input type="number" class="form-control" name="item-unit-price[]" placeholder="0.00">
            </div>
        </div>
        <div class="item-quantity">
            <div class="form-group">
                <input type="number" class="form-control" name="item-quantity[]" placeholder="0.00">
            </div>
        </div>
        <div class="item-total">
            <div class="form-group">
                <input type="number" class="form-control" name="item-total[]" placeholder="0.00" disabled>
            </div>
        </div>
        <button class="btn btn-primary" id="add-item-row">New Line</button>
    </div>

</div>