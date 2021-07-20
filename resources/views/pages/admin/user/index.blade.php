@extends('layouts.admin')

@section('title','کاربران')

@section('content')
    <div class="card">
        <div class="card-header">
            <form>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="id" placeholder="ID" value="{{request('id')}}" class="form-control">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="name" placeholder="نام" value="{{request('name')}}"
                               class="form-control">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="mobile" placeholder="موبایل" value="{{request('mobile')}}"
                               class="form-control">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="email" placeholder="ایمیل" value="{{request('email')}}"
                               class="form-control">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="provider" class="form-control">
                            <option value="">-- ثبت نام از طریق --</option>
                            @foreach(\App\Models\User::Provider as $provider)
                                <option value="{{$provider}}" {{request('provider') == $provider ? 'selected' : ''}}>
                                    {{__('provider.' . $provider)}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        @include('partials.order-by',['order_by' => ['created_at','updated_at','visit','price']])
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">جستجو</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{route('admin.user.list')}}" class="btn btn-warning w-100">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>موبایل</th>
                        <th>ایمیل</th>
                        <th>ثبت نام از</th>
                        <th>ایمیل تایید شده</th>
                        <th>موبایل تایید شده</th>
                        <th>عملیات</th>
                    </tr>

                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->mobile}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{__('provider.' . $user->provider)}}</td>
                            <td>
                                @if($user->email_verified_at)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>
                                @if($user->mobile_verified_at)
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                        عملیات
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.user.edit', $user->id) }}"
                                           data-title="ویرایش کاربر"
                                           data-confirm-text="ویرایش">
                                            ویرایش
                                        </a>
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.note', ['type' => 'User','id' => $user->id]) }}"
                                           data-title="افزودن یادداشت به کاربر {{$user->id}}"
                                           data-confirm-text="افزودن یادداشت">
                                            یادداشت
                                        </a>
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.log', ['type' => 'User','id' => $user->id]) }}"
                                           data-title="لاگ های کاربر {{$user->id}}">
                                            لاگ
                                        </a>
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                           data-bs-target="#defaultModal"
                                           data-path="{{ route('admin.credit.add',$user->id) }}"
                                           data-title="افزودن تراکنش دستی"
                                           data-confirm-text="افزودن تراکنش">
                                            تراکنش دستی
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            @include('partials.paginate',['pages'=>$users])
        </div>
    </div>
@endsection