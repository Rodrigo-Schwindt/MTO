<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Sliders\SlidersController;
use App\Http\Controllers\Sliders\SlidersCreate;
use App\Http\Controllers\Sliders\SlidersEdit;

use App\Http\Controllers\Contact\ContactManager;
use App\Http\Controllers\Contact\ContactInquiriesController;
use App\Http\Controllers\HomeRepresentativesController;
use App\Http\Controllers\HomeMembershipsController;
use App\Http\Controllers\Nosotros\NosotrosAdmin;
use App\Http\Controllers\Nosotros\NosotrosHomeAdmin;
use App\Livewire\Vistas\Nosotros\NosotrosPage;

use App\Livewire\Vistas\Home\Inicio;
use App\Http\Controllers\Novedades\NovCategoriesIndex;
use App\Http\Controllers\Novedades\NovedadesIndex;
use App\Http\Controllers\Novedades\NovedadesCreate;
use App\Http\Controllers\Novedades\NovedadesEdit;
use App\Http\Controllers\Novedades\NovCategoriesCreate;
use App\Http\Controllers\Novedades\NovCategoriesEdit;
use App\Livewire\Vistas\Novedades\NovedadesPublic;
use App\Livewire\Vistas\Novedades\NovedadDetalle;

use App\Livewire\Vistas\Contact\ContactPage;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Usuarios\UsuariosController;

use App\Http\Controllers\Admin\Newsletter\NewsletterCrud;
use App\Http\Controllers\Metadata\MetadataCrud;
use App\Http\Controllers\Servicios\ServiciosController;
use App\Livewire\Vistas\Servicios\ServiciosPublic;
use App\Livewire\Vistas\Servicios\ServicioDetalle;
use App\Http\Controllers\Proyectos\ProjectsController;
use App\Livewire\Vistas\Proyectos\ProjectsPublic;
use App\Livewire\Vistas\Proyectos\ProjectDetail;
use App\Http\Controllers\Clientes\BrandCategoriesController;
use App\Http\Controllers\Clientes\BrandsController;
use App\Livewire\Vistas\Clientes\ClientesPublic;
use App\Http\Controllers\Equipamiento\EquipmentCategoryController;
use App\Http\Controllers\Equipamiento\EquipmentController;
use App\Livewire\Vistas\Equipamiento\EquipmentCategoryDetail;
use App\Livewire\Vistas\Equipamiento\EquipmentPublic;
use App\Livewire\Vistas\Equipamiento\EquipmentDetail;
use App\Http\Controllers\Sectores\SectorsController;
use App\Livewire\Vistas\Sectores\SectoresPublic;
use App\Livewire\Vistas\Sectores\SectorDetalle;
use App\Http\Controllers\Calidad\QualityController;
use App\Livewire\Vistas\Calidad\CalidadPublic;
use App\Http\Controllers\Presupuesto\PresupuestoAdminController;
use App\Http\Controllers\Presupuesto\PresupuestoRequestsController;
use App\Livewire\Vistas\Presupuesto\SolicitarPresupuesto;






    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/', Inicio::class)->name('home');
    Route::get('/nosotros', NosotrosPage::class)->name('nosotros');
    Route::get('/novedades', NovedadesPublic::class)->name('novedades.public');
    Route::get('/novedades/{id}', NovedadDetalle::class)->name('novedad.detalle');
    Route::get('/servicios', ServiciosPublic::class)->name('servicios.public');
    Route::get('/servicios/{slug}', ServicioDetalle::class)->name('servicios.detalle');
    Route::get('/productos', EquipmentPublic::class)->name('productos');
    Route::get('/productos/categoria/{slug}', EquipmentCategoryDetail::class)->name('productos.categoria');
    Route::get('/productos/{slug}', EquipmentDetail::class)->name('productos.detalle');
    Route::get('/sectores', SectoresPublic::class)->name('sectores.public');
    Route::get('/sectores/{slug}', SectorDetalle::class)->name('sectores.detalle');
    Route::get('/proyectos', ProjectsPublic::class)->name('proyectos.public');
    Route::get('/proyectos/{slug}', ProjectDetail::class)->name('proyectos.detalle');
    Route::get('/calidad', CalidadPublic::class)->name('calidad.public');
    Route::get('/clientes', ClientesPublic::class)->name('clientes.public');
    Route::get('/contacto', ContactPage::class)->name('contacto');
    Route::get('/presupuesto', SolicitarPresupuesto::class)->name('presupuesto.public');

    


    
    Route::middleware(['auth', 'is_admin'])->group(function () {

        Route::get('/admin', function () {
            return redirect()->route('sliders.index');
        })->name('admin.dashboard');

        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
            
  
   Route::prefix('admin/sliders')->group(function () {
        Route::get('/', [SlidersController::class, 'index'])->name('sliders.index');
        Route::get('/create', [SlidersCreate::class, 'index'])->name('sliders.create');
        Route::post('/', [SlidersCreate::class, 'store'])->name('sliders.store');
        Route::get('/{id}/edit', [SlidersEdit::class, 'index'])->name('sliders.edit');
        Route::post('/{id}', [SlidersEdit::class, 'update'])->name('sliders.update');
        Route::delete('/{id}', [SlidersController::class, 'destroy'])->name('sliders.destroy');
    });

    Route::get('/admin/home/representantes', [HomeRepresentativesController::class, 'index'])->name('home.representatives.index');
    Route::post('/admin/home/representantes', [HomeRepresentativesController::class, 'save'])->name('home.representatives.save');
    Route::prefix('admin/home/pertenecemos')->group(function () {
        Route::get('/', [HomeMembershipsController::class, 'index'])->name('home.memberships.index');
        Route::get('/create', [HomeMembershipsController::class, 'create'])->name('home.memberships.create');
        Route::post('/', [HomeMembershipsController::class, 'store'])->name('home.memberships.store');
        Route::get('/{id}/edit', [HomeMembershipsController::class, 'edit'])->name('home.memberships.edit');
        Route::put('/{id}', [HomeMembershipsController::class, 'update'])->name('home.memberships.update');
        Route::delete('/{id}', [HomeMembershipsController::class, 'destroy'])->name('home.memberships.destroy');
        Route::post('/{id}/visible', [HomeMembershipsController::class, 'toggleVisible'])->name('home.memberships.visible');
    });

    Route::get('/admin/contacto', [ContactManager::class, 'index'])->name('admin.contacto');
    Route::post('/admin/contacto', [ContactManager::class, 'save'])->name('admin.contacto.save');
    Route::get('/admin/contacto/consultas', [ContactInquiriesController::class, 'index'])->name('admin.contacto.consultas');
    Route::post('/admin/contacto/consultas/{inquiry}/leida', [ContactInquiriesController::class, 'markRead'])->name('admin.contacto.consultas.read');
    Route::delete('/admin/contacto/consultas/{inquiry}', [ContactInquiriesController::class, 'destroy'])->name('admin.contacto.consultas.destroy');

    Route::prefix('admin/presupuesto')->group(function () {
        Route::get('/', [PresupuestoAdminController::class, 'index'])->name('presupuesto.admin.index');
        Route::post('/banner', [PresupuestoAdminController::class, 'saveBanner'])->name('presupuesto.admin.banner.save');
        Route::delete('/banner', [PresupuestoAdminController::class, 'removeBanner'])->name('presupuesto.admin.banner.remove');
        Route::post('/tipos-sistema', [PresupuestoAdminController::class, 'storeSystemType'])->name('presupuesto.system-types.store');
        Route::put('/tipos-sistema/{systemType}', [PresupuestoAdminController::class, 'updateSystemType'])->name('presupuesto.system-types.update');
        Route::delete('/tipos-sistema/{systemType}', [PresupuestoAdminController::class, 'destroySystemType'])->name('presupuesto.system-types.destroy');
        Route::post('/tipos-sistema/{systemType}/visible', [PresupuestoAdminController::class, 'toggleSystemType'])->name('presupuesto.system-types.visible');
        Route::get('/solicitudes', [PresupuestoRequestsController::class, 'index'])->name('presupuesto.requests.index');
        Route::post('/solicitudes/{requestModel}/leida', [PresupuestoRequestsController::class, 'markRead'])->name('presupuesto.requests.read');
        Route::delete('/solicitudes/{requestModel}', [PresupuestoRequestsController::class, 'destroy'])->name('presupuesto.requests.destroy');
    });

    Route::get('admin/nosotros', [NosotrosAdmin::class, 'index'])->name('nosotros.index');
    Route::post('admin/nosotros', [NosotrosAdmin::class, 'save'])->name('nosotros.save');
    Route::delete('admin/nosotros/image/{field}', [NosotrosAdmin::class, 'deleteImage'])->name('nosotros.image.delete');

    Route::get('admin/nosotros/home', [NosotrosHomeAdmin::class, 'index'])->name('nosotros.home.index');
    Route::post('admin/nosotros/home', [NosotrosHomeAdmin::class, 'save'])->name('nosotros.home.save');
    Route::delete('admin/nosotros/home/image', [NosotrosHomeAdmin::class, 'deleteImage'])->name('nosotros.home.image.delete');

    Route::prefix('admin/servicios')->group(function () {
        Route::get('/', [ServiciosController::class, 'index'])->name('servicios.index');
        Route::get('/create', [ServiciosController::class, 'create'])->name('servicios.create');
        Route::post('/', [ServiciosController::class, 'store'])->name('servicios.store');
        Route::post('/banner', [ServiciosController::class, 'saveBanner'])->name('servicios.banner.save');
        Route::delete('/banner', [ServiciosController::class, 'removeBanner'])->name('servicios.banner.remove');
        Route::get('/{id}/edit', [ServiciosController::class, 'edit'])->name('servicios.edit');
        Route::put('/{id}', [ServiciosController::class, 'update'])->name('servicios.update');
        Route::delete('/{id}', [ServiciosController::class, 'destroy'])->name('servicios.destroy');
        Route::post('/{id}/visible', [ServiciosController::class, 'toggleVisible'])->name('servicios.visible');
        Route::post('/{id}/destacado', [ServiciosController::class, 'toggleDestacado'])->name('servicios.destacado');
    });

    Route::prefix('admin/proyectos')->group(function () {
        Route::get('/', [ProjectsController::class, 'index'])->name('proyectos.index');
        Route::get('/create', [ProjectsController::class, 'create'])->name('proyectos.create');
        Route::post('/', [ProjectsController::class, 'store'])->name('proyectos.store');
        Route::post('/banner', [ProjectsController::class, 'saveBanner'])->name('proyectos.banner.save');
        Route::delete('/banner', [ProjectsController::class, 'removeBanner'])->name('proyectos.banner.remove');
        Route::get('/{id}/edit', [ProjectsController::class, 'edit'])->name('proyectos.edit');
        Route::put('/{id}', [ProjectsController::class, 'update'])->name('proyectos.update');
        Route::delete('/{id}', [ProjectsController::class, 'destroy'])->name('proyectos.destroy');
    });

    Route::prefix('admin/equipamiento')->group(function () {
        Route::get('/', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/create', [EquipmentController::class, 'create'])->name('equipment.create');
        Route::post('/', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::post('/banner', [EquipmentController::class, 'saveBanner'])->name('equipment.banner.save');
        Route::delete('/banner', [EquipmentController::class, 'removeBanner'])->name('equipment.banner.remove');
        Route::get('/{id}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('/{id}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/{id}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
        Route::post('/{id}/visible', [EquipmentController::class, 'toggleVisible'])->name('equipment.visible');
    });

    Route::prefix('admin/equipamiento-categorias')->group(function () {
        Route::get('/', [EquipmentCategoryController::class, 'index'])->name('equipment.categories.index');
        Route::get('/create', [EquipmentCategoryController::class, 'create'])->name('equipment.categories.create');
        Route::post('/', [EquipmentCategoryController::class, 'store'])->name('equipment.categories.store');
        Route::get('/{id}/edit', [EquipmentCategoryController::class, 'edit'])->name('equipment.categories.edit');
        Route::put('/{id}', [EquipmentCategoryController::class, 'update'])->name('equipment.categories.update');
        Route::delete('/{id}', [EquipmentCategoryController::class, 'destroy'])->name('equipment.categories.destroy');
        Route::post('/{id}/visible', [EquipmentCategoryController::class, 'toggleVisible'])->name('equipment.categories.visible');
    });

    Route::prefix('admin/sectores')->group(function () {
        Route::get('/', [SectorsController::class, 'index'])->name('sectors.index');
        Route::get('/create', [SectorsController::class, 'create'])->name('sectors.create');
        Route::post('/', [SectorsController::class, 'store'])->name('sectors.store');
        Route::post('/banner', [SectorsController::class, 'saveBanner'])->name('sectors.banner.save');
        Route::delete('/banner', [SectorsController::class, 'removeBanner'])->name('sectors.banner.remove');
        Route::get('/{id}/edit', [SectorsController::class, 'edit'])->name('sectors.edit');
        Route::put('/{id}', [SectorsController::class, 'update'])->name('sectors.update');
        Route::delete('/{id}', [SectorsController::class, 'destroy'])->name('sectors.destroy');
        Route::post('/{id}/visible', [SectorsController::class, 'toggleVisible'])->name('sectors.visible');
    });

    Route::prefix('admin/calidad')->group(function () {
        Route::get('/', [QualityController::class, 'index'])->name('quality.index');
        Route::post('/', [QualityController::class, 'save'])->name('quality.save');
        Route::delete('/banner', [QualityController::class, 'removeBanner'])->name('quality.banner.remove');
        Route::delete('/image', [QualityController::class, 'removeImage'])->name('quality.image.remove');
    });

    Route::prefix('admin/categorias-marcas')->group(function () {
        Route::get('/', [BrandCategoriesController::class, 'index'])->name('brand-categories.index');
        Route::get('/create', [BrandCategoriesController::class, 'create'])->name('brand-categories.create');
        Route::post('/', [BrandCategoriesController::class, 'store'])->name('brand-categories.store');
        Route::get('/{id}/edit', [BrandCategoriesController::class, 'edit'])->name('brand-categories.edit');
        Route::put('/{id}', [BrandCategoriesController::class, 'update'])->name('brand-categories.update');
        Route::delete('/{id}', [BrandCategoriesController::class, 'destroy'])->name('brand-categories.destroy');
        Route::post('/{id}/visible', [BrandCategoriesController::class, 'toggleVisible'])->name('brand-categories.visible');
    });

    Route::prefix('admin/marcas')->group(function () {
        Route::get('/', [BrandsController::class, 'index'])->name('brands.index');
        Route::get('/create', [BrandsController::class, 'create'])->name('brands.create');
        Route::post('/', [BrandsController::class, 'store'])->name('brands.store');
        Route::post('/banner', [BrandsController::class, 'saveBanner'])->name('brands.banner.save');
        Route::delete('/banner', [BrandsController::class, 'removeBanner'])->name('brands.banner.remove');
        Route::get('/{id}/edit', [BrandsController::class, 'edit'])->name('brands.edit');
        Route::put('/{id}', [BrandsController::class, 'update'])->name('brands.update');
        Route::delete('/{id}', [BrandsController::class, 'destroy'])->name('brands.destroy');
        Route::post('/{id}/visible', [BrandsController::class, 'toggleVisible'])->name('brands.visible');
        Route::post('/{id}/destacado', [BrandsController::class, 'toggleDestacado'])->name('brands.destacado');
    });


    Route::get('/admin/novcategorias', [NovCategoriesIndex::class, 'index'])->name('novcategories.index');
    Route::get('/admin/novcategorias/create', [NovCategoriesCreate::class, 'create'])->name('novcategories.create');
    Route::post('/admin/novcategorias/store', [NovCategoriesCreate::class, 'store'])->name('novcategories.store');
    Route::get('/admin/novcategorias/{id}/edit', [NovCategoriesEdit::class, 'edit'])->name('novcategories.edit');
    Route::post('/admin/novcategorias/{id}/update', [NovCategoriesEdit::class, 'update'])->name('novcategories.update');
    Route::delete('/admin/novcategorias/{id}', [NovCategoriesIndex::class, 'delete'])->name('novcategories.delete');

    Route::get('/admin/novedades', [NovedadesIndex::class, 'index'])->name('novedades.index');
    Route::get('/admin/novedades/create', [NovedadesCreate::class, 'create'])->name('novedades.create');
    Route::post('/admin/novedades', [NovedadesCreate::class, 'store'])->name('novedades.store');
    Route::post('/admin/novedades/banner', [NovedadesIndex::class, 'saveBanner'])->name('novedades.banner.save');
    Route::delete('/admin/novedades/banner', [NovedadesIndex::class, 'removeBanner'])->name('novedades.banner.remove');
    Route::delete('/admin/novedades/{id}', [NovedadesIndex::class, 'delete'])->name('novedades.delete');
    Route::post('/admin/novedades/{id}/destacado', [NovedadesIndex::class, 'toggleDestacado'])->name('novedades.toggle');
    Route::get('/admin/novedades/{id}/edit', [NovedadesEdit::class, 'edit'])->name('novedades.edit');
    Route::put('/admin/novedades/{id}', [NovedadesEdit::class, 'update'])->name('novedades.update');










        Route::prefix('admin/usuarios')->group(function () {
        Route::get('/', [UsuariosController::class, 'index'])->name('usuarios.index');
        Route::get('/create', [UsuariosController::class, 'create'])->name('usuarios.create');
        Route::post('/', [UsuariosController::class, 'store'])->name('usuarios.store');
        Route::get('/{id}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit');
        Route::put('/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
        Route::delete('/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');

        });
 

            Route::controller(NewsletterCrud::class)->group(function () {
        Route::get('/admin/newsletter', 'index')->name('admin.newsletter');
        Route::post('/admin/newsletter/send', 'send')->name('admin.newsletter.send');
        Route::post('/admin/newsletter/{id}/toggle', 'toggleActive')->name('admin.newsletter.toggle');
        Route::delete('/admin/newsletter/{id}', 'deleteSubscriber')->name('admin.newsletter.delete');
    });

        Route::get('/admin/metadata', [MetadataCrud::class, 'index'])->name('admin.metadata');
        Route::post('/admin/metadata', [MetadataCrud::class, 'save'])->name('admin.metadata.save');
        Route::delete('/admin/metadata/{id}', [MetadataCrud::class, 'delete'])->name('admin.metadata.delete');

    }); 
