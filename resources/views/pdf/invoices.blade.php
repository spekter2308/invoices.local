<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
</head>
<body>


<style>
    .invoice-pdf {

    }

</style>
<div class="invoice-pdf">

    <div align="left">company</div>
    <div align="right">logo</div>

    <div align="left">customer</div>
    <div align="right">date</div>

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

</body>
<script>
    window.onload = function () {
        window.print();
    }
</script>
</html>
