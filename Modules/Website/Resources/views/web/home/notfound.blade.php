 <section class="middleBlock Wraper500">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-7 mobileVisible">
                    <img src="{{asset('public/images/404.jpg')}}" class="img-responsive">
                </div>
                <div class="col-sm-6 col-lg-5 dataBlock500 ">
                    <h1>404.</h1>
                   <h4>{{trans('countries::home/home.400_h5')}}</h4>
                    <p>{!! trans('countries::home/home.400_p') !!}<a href="#"> {{trans('countries::home/home.let_us_knw')}}</a>.
                    <a href="{{URL('/')}}" class="btn btnGoHome"> {{trans('countries::home/home.go_home')}}</a>
                </div>
                <div class="col-sm-6 col-lg-7 mobileHidden">
                    <img src="{{asset('public/images/404.jpg')}}" class="img-responsive">
                </div>
            </div>
        </div>
    </section>
