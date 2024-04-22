<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;

class ResidenteController extends Controller
{
    public function index(Request $request)
    {
        $residentes = Residente::all();
        return view('residentes.index', compact('residentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci_rsdt' => 'required|unique:residente,ci_rsdt',
            'nombre_rsdt' => 'required',
            'apellidop_rsdt' => 'required',
            'fechanac_rsdt' => 'required|date',
            'telefono_rsdt' => 'required',
            'estado_rsdt' => 'required',
        ]);

        Residente::create($request->all());

        return redirect()->route('residentes.index')->with('success', 'Residente creado exitosamente.');
    }

    public function show($id)
    {
        $residente = Residente::findOrFail($id);
        return view('residentes.show', compact('residente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ci_rsdt' => 'required|unique:residente,ci_rsdt,'.$id,
            'nombre_rsdt' => 'required',
            'apellidop_rsdt' => 'required',
            'fechanac_rsdt' => 'required|date',
            'telefono_rsdt' => 'required',
            'estado_rsdt' => 'required',
        ]);

        $residente = Residente::findOrFail($id);
        $residente->update($request->all());

        return redirect()->route('residentes.index')->with('success', 'Residente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $residente = Residente::findOrFail($id);
        $residente->delete();

        return redirect()->route('residentes.index')->with('success', 'Residente eliminado exitosamente.');
    }
}
