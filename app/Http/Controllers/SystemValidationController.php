<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SystemValidationController extends Controller
{
    /**
     * Ejecuta una validación parcial reactiva de forma dinámica.
     */
    public function validatePartial(Request $request, string $formRequest)
    {
        // 1. Seguridad: Forzar el namespace base de los FormRequests
        $baseNamespace = 'App\\Http\\Requests\\';
        
        // Convertir slashes normales a backslashes (por si el frontend envía 'Categoria/CategoriaGesRequest')
        $formRequest = str_replace('/', '\\', $formRequest);
        $class = $baseNamespace . $formRequest;

        // 2. Verificar que la clase exista y sea hija de FormRequest
        if (!class_exists($class) || !is_subclass_of($class, FormRequest::class)) {
            abort(404, 'El FormRequest solicitado no existe o no es válido.');
        }

        // 3. Instanciar el FormRequest
        /** @var FormRequest $formRequestInstance */
        $formRequestInstance = new $class();

        // Configurar el FormRequest para que se comporte como una petición real
        $formRequestInstance->setMethod($request->method());
        $formRequestInstance->replace($request->except(['_dirty', '_model_id']));
        
        // Resolver el usuario y la ruta actuales para que authorize() o dependencias funcionen
        $formRequestInstance->setUserResolver(fn() => $request->user());
        $formRequestInstance->setRouteResolver(fn() => $request->route());

        // 4. Obtener reglas, mensajes y atributos del FormRequest
        $rules = $formRequestInstance->rules();
        $messages = method_exists($formRequestInstance, 'messages') ? $formRequestInstance->messages() : [];
        $attributes = method_exists($formRequestInstance, 'attributes') ? $formRequestInstance->attributes() : [];

        // 5. Crear el validador con todo el contexto recibido
        $validator = Validator::make(
            $request->except(['_dirty', '_model_id']), 
            $rules, 
            $messages, 
            $attributes
        );

        // 6. Filtrar SOLO los errores de los campos "sucios" (dirty)
        $dirty = $request->input('_dirty', []);
        $filteredErrors = [];

        if ($validator->fails()) {
            $allErrors = $validator->errors()->toArray();
            
            foreach ($allErrors as $field => $messages) {
                if (in_array($field, $dirty, true)) {
                    $filteredErrors[$field] = $messages;
                }
            }
        }

        // 7. Devolver respuesta
        return response()->json([
            'valid' => empty($filteredErrors),
            'errors' => $filteredErrors,
        ]);
    }
}
