<?php

namespace app\Traits;

trait PaginateSortStatusTrait
{
  /* function to check status, order by column and pagination */
  public function PSS($query, $request)
  {

    $sortableColumns = ['name', 'email', 'gender']; // Define columns that can be sorted

    // Sorting logic
    if ($request->has('sort_by') && in_array($request->sort_by, $sortableColumns)) {
      $sortDirection = $request->has('sort_dir') && $request->sort_dir === 'desc' ? 'desc' : 'asc';
      $query->orderBy($request->sort_by, $sortDirection);
    }

    // Set default per page value to 10 if 'per_page' parameter is not present or invalid
    $perPage = $request->filled('per_page') ? intval($request->per_page) : 10;

    if ($request->has("status") && $request->filled('status')) {
      $query->where('is_active', $request->status);
    }

    $appendable = [];

    // Create appendable array for non-empty query parameters except 'page' and '_token'
    foreach ($request->except(['page', '_token', 'is_ajax']) as $key => $value) {
      if (!empty($value)) {
        $appendable[$key] = $value;
      }
    }

    return $query->paginate($perPage)->appends($appendable);
  }
}
