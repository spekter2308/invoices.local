<style>
    .invoice-pdf {
    
    }
    
</style>
<div class="invoice-pdf">

    <table>
        <tr>
            <td>company</td>
            <td>logo</td>
        </tr>
        <tr>
            <td>customer</td>
            <td>date</td>
        </tr>
    </table>
    
    <table>
        <thead>
            <tr>
                <th>item</th>
                <th>description</th>
                <th>unit price</th>
                <th>quantity</th>
                <th>tax</th>
                <th>amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>item_body</td>
                <td>description_body</td>
                <td>unit_price_body</td>
                <td>quantity_body</td>
                <td>tax_body</td>
                <td>amount_body</td>
            </tr>
    
            <tr>
                <td>notes</td>
            </tr>
        </tbody>
    </table>

</div>
<script>
    window.onload = function () {
        window.print();
    }
</script>