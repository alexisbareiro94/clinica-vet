<div class="bg-gray-100 py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold">Planes y Precios</h1>
        <p class="text-gray-600">Elegí el plan que mejor se adapte a tus necesidades</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 px-6">

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            @include('includes.planes.gratis')
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            @include('includes.planes.basico')
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden border-2 border-gray-200 ">
            @include('includes.planes.estandar')
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            @include('includes.planes.avanzado')
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            @include('includes.planes.premium')
        </div>

    </div>
</div>
