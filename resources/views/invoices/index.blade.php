@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <table class="table">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                </div>

                            </div>
                        </th>
                        <th scope="col">
                            Invoices
                        </th>
                        <th scope="col">Customer</th>
                        <th scope="col">Company</th>
                        <th scope="col">Date</th>
                        <th scope="col">Days</th>
                        <th scope="col">Toral</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Status</th>
                        <th scope="col">Mark Paid</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>
                            <div class="form-group row">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck1">
                                    </div>
                            </div>
                        </th>
                        <th scope="row">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                </div>
                                Name
                            </div>
                        </th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>test</td>
                        <td>test</td>
                        <td>test</td>
                        <td>test</td>
                        <td>test</td>
                        <td>test</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection