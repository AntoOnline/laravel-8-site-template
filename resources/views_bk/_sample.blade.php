@extends('shared.header')

@section('content')

<section class="content" >
    <div class="container-fluid">
        <h3 class="mt-4 mb-4">URL Encode/Decode</h3>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 pt-4">
                <div class="card h-100 ">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-square-root-alt mr-1"></i>
                            --------->Title
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        --------->Code

                    </div>
                    <!-- /.col -->
                </div>
            </div>


        </div>
        <!-- /.col -->

    </div>
    <!-- /.row -->
</section>
<section class="content" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12 pt-4">
                <div class="card h-100 ">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-book-open mr-1"></i>
                            Frequently Asked Questions
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body ">

                        <div class="row">
                            <div class="col-12" id="accordion">
                                <div class="card card-primary card-outline">
                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                1. Aenean massa
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseOne" class="collapse" data-parent="#accordion" style="">
                                        <div class="card-body">
                                            Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                2. Aenean massa
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseTwo" class="collapse" data-parent="#accordion" style="">
                                        <div class="card-body">
                                            Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                3. Donec quam felis
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseThree" class="collapse" data-parent="#accordion" style="">
                                        <div class="card-body">
                                            Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-warning card-outline">
                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseFour">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                4. Donec pede justo
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseFour" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-warning card-outline">
                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                5. In enim justo
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseFive" class="collapse" data-parent="#accordion" style="">
                                        <div class="card-body">
                                            In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-warning card-outline">
                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseSix">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                6. Integer tincidunt
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseSix" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-danger card-outline">
                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseSeven" aria-expanded="false">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                7. Aenean leo ligula
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseSeven" class="collapse " data-parent="#accordion" style="">
                                        <div class="card-body">
                                            Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-danger card-outline">
                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseEight">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                8. Aliquam lorem ante
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseEight" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-danger card-outline">
                                    <a class="d-block w-100" data-toggle="collapse" href="#collapseNine">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                9.  Quisque rutrum
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="collapseNine" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-3 text-center">
                                <p class="lead">
                                    <a href="https://anto.online/contact-anto-online/">Contact us</a>,
                                    if you did not find the right answer or you have a other question?<br>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->
    </div>
    <!-- container fluid -->

</section>

@endsection
