<div class="span5">
  {{ Form::open(array('route' => 'admin.artilheiro.store', 'class' => 'form-horizontal modal-form')) }}
    <div class="item">
      <div class="control-group">
        {{ Form::label('time_id', 'Time', array('class' => 'control-label')) }}

        <div class="controls">
          {{ Form::select('time_id', $times, null, array( 'class' => 'input-xlarge')) }}
        </div>
      </div>
      <div class="control-group">
        {{ Form::label('nome', 'Nome', array('class' => 'control-label')) }}

        <div class="controls">
          {{ Form::text('nome', '', array( 'class' => 'input-xlarge')) }}
        </div>
      </div>
      <div class="control-group">
        {{ Form::label('artilheiro', 'É Artilheiro?', array('class' => 'control-label')) }}

        <div class="controls">
          {{ Form::checkbox('artilheiro', 1) }}
        </div>
      </div>
    </div>
    <div class="item">
      <div class="control-group">
        <label class="control-label" for="fileupload">Foto</label>

        <div class="controls">
          {{ Form::hidden('foto', null, array('class' => 'fileupload-hidden')) }}
          <span class="btn btn-success fileinput-button fileupload-button" id="fileupload">
            <span><i class="icon-plus icon-white"></i>Adicionar imagem</span>
          </span>

          <div class="progress progress-success progress-striped active fade">
            <div class="bar" style="width:0%;"></div>
          </div>
        </div>
      </div>
      <div class="control-group">
        <div class="row-fluid">
          <ul class="thumbnails">
            <li class="span3 thumbnail">
              <img data-src="holder.js/260x100/text: "
                   alt="" class="fileupload-thumbnail">
            </li>
          </ul>
        </div>
      </div>
    </div>
  {{ Form::close() }}
  @include('util.upload_form')
</div>
<script type="text/javascript">
    var min_width = 328,
        min_height = 332,
        max_width = 656,
        max_height = 664;
</script>
