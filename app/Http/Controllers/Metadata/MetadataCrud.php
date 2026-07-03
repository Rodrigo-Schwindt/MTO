<?php

namespace App\Http\Controllers\Metadata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Metadata;
use App\Models\Novedades;
use App\Models\EquipmentProduct;
use App\Models\ServiceCategory;

class MetadataCrud extends Controller
{
    private array $sections = [
        'home', 'nosotros', 'servicios', 'equipamiento',
        'novedades', 'sectores', 'proyectos', 'calidad',
        'clientes', 'contacto', 'presupuesto',
    ];

    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $sectionOrder = array_flip($this->sections);
        array_walk($sectionOrder, fn(&$v) => $v++);

        $case = 'CASE ';
        foreach ($sectionOrder as $sec => $ord) {
            $case .= "WHEN section = '$sec' THEN $ord ";
        }
        $case .= 'ELSE 999 END';

        $query = Metadata::query();

        if ($search) {
            $like = "%$search%";
            $query->where(function ($q) use ($like) {
                $q->where('section', 'like', $like)
                  ->orWhere('keywords', 'like', $like)
                  ->orWhere('description', 'like', $like);
            });
        }

        $items = $query->orderByRaw($case)
                       ->orderBy('section')
                       ->paginate(10)
                       ->withQueryString();

        return view('livewire.metadata.crud', [
            'items'        => $items,
            'search'       => $search,
            'sections'     => $this->sections,
            'novedades'    => Novedades::orderBy('title')->get(),
            'equipamientos'=> EquipmentProduct::orderBy('title')->get(['id', 'title']),
            'servicios'    => ServiceCategory::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function save(Request $request)
    {
        $metadataType = $request->metadataType;
        $metadataId   = $request->metadataId;

        $prefixMap = [
            'novedad'      => 'novedad-',
            'equipamiento' => 'equipamiento-',
            'servicio'     => 'servicio-',
        ];

        if (isset($prefixMap[$metadataType])) {
            if (!$request->itemId) {
                return back()->withErrors(['itemId' => 'Debes seleccionar un elemento.'])->withInput();
            }
            $request->merge(['section' => $prefixMap[$metadataType] . $request->itemId]);
        }

        $rules = [
            'section'      => 'required|string|max:255|unique:metadata,section,' . ($metadataId ?? 'NULL'),
            'keywords'     => 'required|string',
            'description'  => 'required|string|max:160',
            'metadataType' => 'required|in:section,novedad,equipamiento,servicio',
        ];

        if (isset($prefixMap[$metadataType])) {
            $rules['itemId'] = 'required|integer';
        }

        $data = $request->validate($rules);

        Metadata::updateOrCreate(
            ['id' => $metadataId],
            [
                'section'     => $data['section'],
                'keywords'    => $data['keywords'],
                'description' => $data['description'],
            ]
        );

        return redirect()->route('admin.metadata')
            ->with('success', 'Metadato guardado correctamente');
    }

    public function delete($id)
    {
        Metadata::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
