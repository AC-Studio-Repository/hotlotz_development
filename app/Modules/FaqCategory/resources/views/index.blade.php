@extends('appshell::layouts.default')

@section('title')
    {{ __('Discover') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
            <table class="table table-striped table-hover">
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('about_us.about_uss.index') }}" class="btn btn-outline-primary">{{ __('ABOUT US') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('ABOUT US') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                    <td><a href="{{ route('how_to_buy.how_to_buys.index') }}" class="btn btn-outline-primary">{{ __('HOW TO BUY') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('HOW TO BUY') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('how_to_sell.how_to_sells.index') }}" class="btn btn-outline-primary">{{ __('HOW TO SELL') }}</a></td>
                     @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('HOW TO SELL') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('location_cms.location_cmss.index') }}" class="btn btn-outline-primary">{{ __('LOCATION') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('LOCATION') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                    <td><a href="{{ route('our_team.our_teams.showlist') }}" class="btn btn-outline-primary">{{ __('TEAM') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('TEAM') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('strategic_partner.strategic_partners.showlist') }}" class="btn btn-outline-primary">{{ __('STRATEGIC PARTNERS') }}</a></td>
                     @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('STRATEGIC PARTNERS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('faq_category.faqcategories.bloglist') }}" class="btn btn-outline-primary">{{ __('Media Coverage') }}</a></td>
                     @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Media Coverage') }}</a></td>
                    @endcan
                </tr>
                <!-- <tr>
                    <td>{{ __('Cookies Policy') }}</td>
                </tr>
                <tr>
                    <td>{{ __('CARRERS') }}</td>
                </tr>-->
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('faq_category.faqcategories.showlist') }}" class="btn btn-outline-primary">{{ __('FAQS') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('FAQS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('glossary.glossarys.list') }}" class="btn btn-outline-primary">{{ __('GLOSSARY') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('GLOSSARY') }}</a></td>
                    @endcan
                </tr>
            </table>
        </div>
    </div>

@stop
