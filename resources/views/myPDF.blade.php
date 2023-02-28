<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $invoiceNumber }}</title>
    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span,
        div {
            font-family: DejaVu Sans;
            font-size: 10px;
            font-weight: normal;
        }

        th,
        td {
            font-family: DejaVu Sans;
            font-size: 10px;
        }

        .panel {
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .panel-default {
            border-color: #ddd;
        }

        .panel-body {
            padding: 15px;
        }

        table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 0px;
            border-spacing: 0;
            border-collapse: collapse;
            background-color: transparent;
        }

        thead {
            text-align: left;
            display: table-header-group;
            vertical-align: middle;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        }
    </style>
</head>

<body>
    <header>
        <div style="position:absolute; left:0pt; width:250pt;">
            <img class="img-rounded" height="50px" src="C:\xampp\htdocs\invoice-backend\public\images\logo.png">
        </div>
        <div style="margin-left:300pt;">
            <b>Date: </b> {{ $date }}<br />
            @if ($invoiceNumber)
            <b>Invoice #: </b> {{ $invoiceNumber }}
            @endif
            <br />
        </div>
        <br />
        <h2>{{ $clientName }} {{ $invoiceNumber ? '#' . $invoiceNumber : '' }}</h2>
    </header>
    <main>
        <div style="clear:both; position:relative;">
            <div style="position:absolute; left:0pt; width:250pt;">
                <h4>Business Details:</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        {{ $userName }}<br />
                        ID: {{ $ruc }}<br />
                        {{ $userPhone }}<br />
                        {{ $userAddress }}<br />
                        {{ $userZipCode }} {{ $city }}
                        {{ $country }}<br />
                    </div>
                </div>
            </div>
            <div style="margin-left: 300pt;">
                <h4>Customer Details:</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        {{ $clientName }}<br />
                        ID: {{ $clientId }}<br />
                        {{ $clientPhone }}<br />
                        {{ $clientAddress }}<br />
                        {{ $clientZipCode }} {{ $city }}
                        {{ $country }}<br />
                    </div>
                </div>
            </div>
        </div>
        <h4>Items:</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>                 
                    <td>{{ $item['item'] }}</td>
                    <td>${{ $item['price'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ $item['amount'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="clear:both; position:relative;">
            @if($notes)
            <div style="position:absolute; left:0pt; width:250pt;">
                <h4>Notes:</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        {{ $notes }}
                    </div>
                </div>
            </div>
            @endif
            <div style="margin-left: 300pt;">
                <h4>Total:</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><b>Subtotal</b></td>
                            <td>${{ $subTotal }}</td>
                        </tr>
                        <tr>
                            <td><b>Tax 12%</b></td>
                            <td>${{ $tax }}</td>
                        </tr>
                        <tr>
                            <td><b>TOTAL</b></td>
                            <td><b>${{ $total }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($footNote)
        <br /><br />
        <div class="well">
            {{ $footNote }}
        </div>
        @endif
    </main>

    <!-- Page count -->
    <script type="text/php">
        if (isset($pdf) && $GLOBALS['with_pagination'] && $PAGE_COUNT > 1) {
                $pageText = "{PAGE_NUM} of {PAGE_COUNT}";
                $pdf->page_text(($pdf->get_width()/2) - (strlen($pageText) / 2), $pdf->get_height()-20, $pageText, $fontMetrics->get_font("DejaVu Sans, Arial, Helvetica, sans-serif", "normal"), 7, array(0,0,0));
            }
        </script>
</body>

</html>