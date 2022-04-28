<?php


namespace App\Http\Controllers;


use App\Repositories\ImportRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    private ImportRepositoryInterface $importRepository;

    public function __construct(ImportRepositoryInterface $importRepository)
    {
        $this->importRepository = $importRepository;
    }

    public function storeStudents(Request $request) : JsonResponse{
        $this->authorize('import users');
        $this->importRepository->storeStudents($request);
        return response()->json(["message" => "students imported successfully"]);
    }

    public function storeStaff(Request $request) : JsonResponse{
        $this->authorize('import users');
        $this->importRepository->storeStaff($request);
        return response()->json(["message" => "staff imported successfully"]);
    }


}
