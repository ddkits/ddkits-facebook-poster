@extends('ddkitsfacebook::app')

@section('title', 'DDKits Facebook Panel')

@section('content')
        <!-- edit form column -->
        @if (Auth::id())
	    <div class="bg-white col-lg-12 has-shadow text-center">
            <h1>DDKits Facebook Panel</h1>
            {{ Form::open(array('route' => 'admin-ddkits-facebook.store', 'method'=>'POST')) }}
            <table class="table table-bordered col-lg-12">
	    		<tbody>

	    			<tr>
	    				<td>
                            {{ Form::text('title','', ['class'=>'form-control', 'placeholder'=>'Title']) }}
                        </td>
                        <td>
                            {{ Form::text('appId', '', ['class'=>'form-control', 'placeholder'=>'appId']) }}
                       </td>
                       <td>
                             {{ Form::text('appSecret', '', ['class'=>'form-control', 'placeholder'=>'appSecret']) }}
                        </td>
                        <td>
                            {{ Form::text('pageId','', ['class'=>'form-control', 'placeholder'=>'pageId']) }}
                        </td>
                        <td>
                            {{ Form::text('userAccessToken', '', ['class'=>'form-control', 'placeholder'=>'userAccessToken']) }}
                        </td>

	    				<td>

                        {{ Form::number('uid', Auth::id(), ['class'=>'form-control hidden', 'hidden'=>'true']) }}
	    				{{ Form::submit('Create', ['class'=>'form-control btn-success']) }}
	    				</td>

	    			</tr>
	    		</tbody>



            </table>
            {{ Form::close() }}
            <hr>
	    	 <table  class="table table-bordered col-lg-12">
                 <thead><tr>
                    <th class="info"> Title </th>
                    <th class="info"> appId </th>
                    <th class="info"> pageId </th>
                    <th class="info">Added by UID</th>
                    <th class="info danger">Delete</th></tr>
                </thead>


          @foreach($settings as $settings)
          {{ Form::open(['route' => ['admin-ddkits-facebook.destroy', $settings->id ], 'method'=>'DELETE', 'id'=>'newMsgForm']) }}
             <tr>
                 <td> {{ $settings->title }}</td>
                <td> {{ $settings->appId }}</td>
                <td> {{ $settings->pageId }}</td>
                <td>{{ (Auth::user()->find($settings->uid)) ? Auth::user()->find($settings->uid)->firstname : '' }}</td>
                <td> {{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}</td>
            </tr>

			{{ Form::close()  }}
			@endforeach
			</table>

			<hr>
			<h1>Site Extra</h1>
	    	<table class="table table-bordered col-lg-12">
	    		<tbody>
	    			<tr>
	    				<td>
	    					Test Post
	    				</td>
	    				<td>
                            {{ Form::open(array('route' => 'admin-ddkits-facebook.post')) }}
                            This would send a test similar to this domain.
		    				{{ Form::submit('Send a test', ['class'=>'form-control btn-success']) }}
		    				{{ Form::close() }}
	    				</td>

                    </tr>
                    <tr>
	    				<td>
	    					Post A message to all pages
	    				</td>
	    				<td>
                            {{ Form::open(array('route' => 'admin-ddkits-facebook.postlive')) }}
                            Custom Post to all Pages assigned to this account.
                            {{ Form::textarea('message', '', ['class'=>'form-control', 'placeholder'=>'Message']) }}
                            {{ Form::text('link', '', ['class'=>'form-control', 'placeholder'=>'Link']) }}
                            {{ Form::number('uid', Auth::id(), ['class'=>'form-control hidden', 'hidden'=>'true']) }}
		    				{{ Form::submit('Post', ['class'=>'form-control btn-success']) }}
		    				{{ Form::close() }}
	    				</td>

	    			</tr>
	    		</tbody>
			</table>


          </div>

          @else
      <div class="bg-white col-lg-12 has-shadowtext-center">
             <h1>DDKits Facebook Panel for Logged users only</h1>
            <ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-3089600610336467"
     data-ad-slot="3292938672"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    </div>
@endif
@endsection
