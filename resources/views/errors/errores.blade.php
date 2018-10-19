@if(count($errors)>0)
    <div class="alert BtnRed msj-error" role="alert">
    	<button type="button" class="close close-alert">
		  <span aria-hidden="true">&times;</span>
		</button>
        <ul>
            @foreach($errors->all() as $error)
                <li><strong>{{ $error }}</strong></li>
            @endforeach
        </ul>
    </div>
@endif