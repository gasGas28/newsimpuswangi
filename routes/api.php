use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Farmasi\MasterObatController;

Route::apiResource('obat', MasterObatController::class);