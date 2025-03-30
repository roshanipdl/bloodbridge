{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('donor') }}"><i class="nav-icon la la-user-plus"></i> Donors</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('recipient') }}"><i class="nav-icon la la-user"></i> Recipients</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('blood-request') }}"><i class="nav-icon la la-tint"></i> Blood requests</a></li>