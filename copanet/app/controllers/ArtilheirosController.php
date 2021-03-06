<?php

class ArtilheirosController extends BaseController
{

    protected $title = 'Artilheiros';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('artilheiros.index')->with(array(
            'entities' => Artilheiro::all(),
            'times' => $this->getTimesSelect(true),
            'title' => $this->title
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('artilheiros.create')->with('times', $this->getTimesSelect());
    }

    public function foto()
    {
        if(!Input::get('ajaxAction')) {
            //@TODO fail
        }

        switch (Input::get('ajaxAction')) {
            case 'upload':
                return $this->uploadFoto();
                break;
            case 'cropForm':
                return $this->getCropForm();
                break;
            case 'crop':
                return $this->cropFoto();
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if(!Time::find(Input::get('time_id'))) {
            return Response::json(array(
                'success' => false,
                'messages' => 'Time não encontrado'
            ));
        }
        $entity = new Artilheiro(Input::all());
        if(!$entity->save()){
            return Response::json(array(
                'success' => false,
                'messages' => $entity->errors()->all()
            ));
        }

        return Response::json(array(
            'success' => true
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function time($id)
    {
        $html = '';
        if($id <= 0) {
            foreach (Artilheiro::all() as $entity) {
                $html .= View::make('artilheiros._partials.table_row')->with('entity', $entity);
            }
        } else {
            $time = Time::has('artilheiros')->find($id);
            if($time) {
                foreach ($time->artilheiros()->get() as $entity) {
                    $html .= View::make('artilheiros._partials.table_row')->with('entity', $entity);
                }
            }
        }
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $entity = Artilheiro::find($id);
        if (!$entity) {
            App::abort('Artilheiro não encontrado', 404);
        }
        return View::make('artilheiros.edit')->with(array('entity' => $entity, 'times' => $this->getTimesSelect()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $time = Time::find(Input::get('time_id'));
        if(!$time) {
            return Response::json(array(
                'success' => false,
                'messages' => 'Time não encontrado'
            ));
        }
        $entity = Artilheiro::find($id);
        if (!$entity) {
            return Response::json(array(
                'success' => false,
                'messages' => 'Artilheiro não encontrado'
            ));
        }

        $entity->fill(Input::all());
        if(!$entity->save()){
            return Response::json(array(
                'success' => false,
                'messages' => $entity->errors()->all()
            ));
        }

        return Response::json(array(
            'success' => true
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $entity = Artilheiro::find($id);
        if(!$entity) {
            return Response::json(array(
                    'success' => false,
                    'message' => 'Artilheiro não encontrado',
            ));
        }

        if(!$entity->delete()) {
            return Response::json(array(
                    'success' => false,
                    'message' => 'Não foi possível remover o item',
            ));
        }

        return Response::json(array(
                'success' => true,
        ));
    }

    public function gols($id)
    {
        $entity = Artilheiro::find($id);
        if (!$entity) {
            App::abort('Artilheiro não encontrado', 404);
        }

        return View::make('gols.form')->with(array(
            'entity' => $entity,
            'route' => array('admin.artilheiro.gols.save', $entity->id)
        ));
    }

    public function golsSave($id)
    {
        $entity = Artilheiro::find($id);
        if (!$entity) {
            App::abort('Artilheiro não encontrado', 404);
        }

        $entity->gols = Input::get('gols', (int) $entity->gols);
        $entity->save();

        return Response::json(array(
            'success' => true
        ));
    }

    /**
     * Get times select array
     * @return array
     */
    protected function getTimesSelect($filtered = false)
    {
        $departamentos = Departamento::has('times')->get();
        $select = array();
        foreach ($departamentos as $departamento) {
            foreach ($departamento->times as $time) {
                if($filtered && $time->hasMany('artilheiro')->count() == 0) {
                    continue;
                }
                $select[$departamento->nome][$time->id] = $time->nome;
            }
        }

        return $select;
    }

    /**
     * Handle foto upload
     * @return Response
     */
    protected function uploadFoto()
    {
        $foto = Input::file('image');
        try {
            $result = Upload::save($foto);
        } catch ( Exception $ex) {
            return Response::json(array(
                'success' => false,
                'messages' => $ex->getMessage()
            ));
        }

        $image = $this->loadFoto($result['path']);
        ob_start();
        print View::make('util.crop_form')->with(array_merge(array('image' => $image), $result));
        $form = ob_get_clean();
        return Response::json(array(
            'success' => true,
            'url' => url($result['url']),
            'form' => $form
        ));
    }

    protected function cropFoto()
    {
        $imageResource = $this->loadFoto();
        $width = ceil(Input::get('w_sel'));
        $height = ceil(Input::get('h_sel'));
        $destX = Input::get('x_d', 0);
        $destY = Input::get('y_d', 0);
        $maxWidth = Input::get('max_width', 0);
        $maxHeight = Input::get('max_height', 0);
        $imageResource->crop($width, $height, $destX, $destY);
        $path = $imageResource->dirname . '/' . $imageResource->filename . "-{$width}x{$height}." . $imageResource->extension;
        if($maxWidth && $maxHeight) {
            $imageResource->resize($maxWidth, $maxHeight);
        }
        $imageResource->save($path, 100);

        $url = Upload::getUrlFromPath($path);
        return Response::json(array(
            'success' => true,
            'url' => $url,
            'image' => url($url),
        ));
    }

    protected function loadFoto($path = '')
    {
        $image = ($path) ? $path : Input::get('image');
        if(!$image) {
            throw new Exception('Imagem não encontrada', 500);
        }
        $imageResource = Image::make($image);
        if(!$imageResource) {
            throw new Exception('Erro ao abrir imagem', 500);
        }

        return $imageResource;
    }

}
