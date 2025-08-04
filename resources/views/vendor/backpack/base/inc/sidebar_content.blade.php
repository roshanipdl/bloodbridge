{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('donor') }}"><i class="nav-icon la la-hand-holding-heart"></i> Donors</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('recipient') }}"><i class="nav-icon la la-user-friends"></i> Recipients</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('blood-request') }}"><i class="nav-icon la la-heartbeat"></i> Blood requests</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('donation-request') }}"><i class="nav-icon la la-medkit"></i> Donation requests</a></li>