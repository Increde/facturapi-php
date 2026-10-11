# Fase 7: Estabilización de Pruebas Unitarias (Testing Exhaustivo)

## Resumen
En esta fase se han refactorizado y reparado todas las pruebas unitarias (PHPUnit) que habían quedado desactualizadas debido a los cambios estructurales introducidos en la **Fase 4 (Inyección de Dependencias)** y la **Fase 5 (Excepciones)**. El resultado es una suite completamente funcional (100% verde) con 55 tests pasando exitosamente.

## Cambios Realizados

1. **Refactorización de Instanciación de Recursos**:
   - Anteriormente, las pruebas inyectaban el `FakeHttpClient` pasando un arreglo `['httpClient' => $client]` directo a los constructores de los recursos (ej. `new Invoices(...)`).
   - *Corrección*: Se migraron todos los tests para instanciar el cliente principal `Facturapi` y acceder a los recursos mediante sus métodos, respetando la nueva arquitectura de composición: `(new Facturapi('key', ['httpClient' => $client]))->invoices()`.

2. **Ajuste de Aserciones y Accesores**:
   - Dado que el SDK ahora retorna arreglos asociativos en lugar de objetos `stdClass` (`json_decode(true)`), se actualizaron las aserciones en los tests para acceder a propiedades con la sintaxis de arreglo (ej. `$result['id']` en lugar de `$result->id`). Esto incluye tests en `WebhooksTest`, `InvoicesTest`, entre otros.

3. **Reparación de Endpoints y Query Parameters**:
   - Se corrigieron bugs introducidos durante las refactorizaciones previas en la codificación de URIs (ej. validación del endpoint `domain-check` para el recurso de Organizaciones).
   - Se añadió soporte completo en `FacturapiHttpClient` para el pase y serialización de parámetros de consulta (`?array $query = null`) en los métodos `post()` y `delete()`.
   - Se parcheó la generación de los query strings (`http_build_query`) para que serialicen apropiadamente tipos de datos booleanos a `'true'` o `'false'`, y remuevan el formato de arreglos numéricos (ej. `status[0]=valid` -> `status=valid`), cumpliendo con lo que espera el API de Facturapi.

4. **Validaciones en Métodos Multipart**:
   - Se rediseñó el mecanismo de solicitudes HTTP con `multipart/form-data` para aceptar métodos configurables (`PUT` o `POST`).
   - Se implementaron y validaron las extracciones correctas de llaves como `cer` y `key` para `uploadCertificate` y `uploadLogo` en `Organizations.php`.

## Beneficios a Largo Plazo
- **Cobertura y Fiabilidad**: Mantener la suite de tests 100% libre de errores garantiza que los refactors arquitectónicos de las siguientes fases no romperán silenciosamente la funcionalidad del SDK.
- **Alineación Arquitectónica**: Las pruebas ahora actúan como un reflejo fiel de cómo el usuario final utilizará la librería (`$facturapi->invoices()->create()`), validando verdaderamente el comportamiento del `entrypoint`.
