namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::latest()->paginate(10);
        return view('assets.index', compact('assets'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_code' => 'required|unique:assets',
            'name' => 'required',
            'category' => 'required',
            'condition' => 'required',
        ]);

        Asset::create($request->all());
        return redirect()->route('assets.index')
            ->with('success', 'Aset IT berhasil ditambahkan.');
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'asset_code' => 'required|unique:assets,asset_code,'.$asset->id,
            'name' => 'required',
            'category' => 'required',
            'condition' => 'required',
        ]);

        $asset->update($request->all());
        return redirect()->route('assets.index')
            ->with('success', 'Data Aset IT berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')
            ->with('success', 'Aset IT berhasil dihapus.');
    }
}