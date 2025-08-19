  @if (!in_array($d->proj_status, ['Cancelled', 'Done']))
      <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
          <div class="btn-group" role="group">
              <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  Action
              </button>
              <ul class="dropdown-menu">
                  @if ($d->proj_status == 'Draft')
                      <li>
                          <form class="d-inline" action="{{ route('project.update.status', $d->id) }}" method="POST"
                              id="form-request{{ $d->id }}">
                              @csrf
                              @method('PUT')
                              <input type="hidden" id="proj_status{{ $d->id }}" name="proj_status">
                              <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                                  onclick="update_status('Pre Sales', {{ $d->id }}); return false;">
                                  Run Project
                              </a>
                          </form>
                      </li>
                  @endif
                  <li>
                      <a class="dropdown-item" href="{{ route('project.edit', $d->id) }}">
                          Edit
                      </a>
                  </li>
                  <li>
                      <form class="d-inline" action="{{ route('project.cancel', $d->id) }}" method="POST"
                          id="form-cancel{{ $d->id }}">
                          @csrf
                          @method('PUT')
                          <input type="hidden" id="cancel-message{{ $d->id }}" name="message">
                          <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                              onclick="cancel({{ $d->id }}); return false;">
                              Cancel
                          </a>
                      </form>
                  </li>
                  <li>
                      <form class="d-inline" action="{{ route('project.destroy', $d->id) }}" method="POST"
                          id="form-delete{{ $d->id }}">
                          @csrf
                          @method('DELETE')
                          <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                              onclick="delete_data({{ $d->id }}); return false;">
                              Delete
                          </a>
                      </form>
                  </li>
              </ul>
          </div>
      </div>
  @else
      <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
          <div class="btn-group" role="group">
              <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  Action
              </button>
              <ul class="dropdown-menu">
                  @if (Auth::user()->hasRole('superadmin'))
                      <li>
                          <a class="dropdown-item" href="{{ route('project.edit', $d->id) }}">
                              Edit
                          </a>
                      </li>
                      <li>
                          <form class="d-inline" action="{{ route('project.cancel', $d->id) }}" method="POST"
                              id="form-cancel{{ $d->id }}">
                              @csrf
                              @method('PUT')
                              <input type="hidden" id="cancel-message{{ $d->id }}" name="message">
                              <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                                  onclick="cancel({{ $d->id }}); return false;">
                                  Cancel
                              </a>
                          </form>
                      </li>
                      <li>
                          <form class="d-inline" action="{{ route('project.destroy', $d->id) }}" method="POST"
                              id="form-delete{{ $d->id }}">
                              @csrf
                              @method('DELETE')
                              <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                                  onclick="delete_data({{ $d->id }}); return false;">
                                  Delete
                              </a>
                          </form>
                      </li>
                  @else
                      @if ($d->proj_status == 'Cancelled')
                          Cancelled
                      @else
                          Done
                      @endif
                  @endif
              </ul>
          </div>
      </div>
  @endif
