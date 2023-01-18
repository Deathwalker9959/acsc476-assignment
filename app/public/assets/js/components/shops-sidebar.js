import { emitTeamIdUpdated } from "../mixins/events.js";

let data = store({
    selectedTeam: null,
});

let template = () => {
    let { selectedTeam } = data;

    if (selectedTeam)
        return "";

    return /*html*/ `
    <div class="d-md-none container my-3">
    <div class="row row-cols-3 px-0">
        <div class="col d-flex mx-auto px-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSort" aria-expanded="false" aria-controls="collapseFilters">
                Sort
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down" viewBox="0 0 16 16">
                    <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z" />
                </svg>
            </button>
        </div>
        <div class="col d-flex mx-auto px-0">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
                Filters
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                </svg>
            </button>
        </div>
        <div class="col">
        </div>
    </div>

</div>

<div class="collapse" id="collapseSort">
    <div class="container my-3">
        <form>
            <div class="form-group">
                <legend>Sorting</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sorting" id="default" value="default" checked>
                    <label class="form-check-label" for="default">
                        Default
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sorting" id="delivery-cost" value="delivery-cost">
                    <label class="form-check-label" for="delivery-cost">
                        Delivery Cost
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="collapse" id="collapseFilters">
    <div class="container my-3">
        <form>
            <div class="form-group">
                <legend>Cuisines</legend>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="grill" id="grill">
                    <label class="form-check-label" for="grill">
                        Grill
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="coffee" id="coffee">
                    <label class="form-check-label" for="coffee">
                        Coffee
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="pizza" id="pizza">
                    <label class="form-check-label" for="pizza">
                        Pizza
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="salad" id="salad">
                    <label class="form-check-label" for="salad">
                        Salad
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="burgers" id="burgers">
                    <label class="form-check-label" for="burgers">
                        Burgers
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="crepes" id="crepes">
                    <label class="form-check-label" for="crepes">
                        Crepes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="sandwich" id="sandwich">
                    <label class="form-check-label" for="sandwich">
                        Sandwich
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="asian" id="asian">
                    <label class="form-check-label" for="asian">
                        Asian
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="american" id="american">
                    <label class="form-check-label" for="american">
                        American
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="international" id="international">
                    <label class="form-check-label" for="international">
                        International
                    </label>
                </div>
            </div>
        </form>
    </div>
    <div class="container my-3">
        <form>
            <div class="form-group">
                <legend>Filter</legend>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="free-delivery" id="free-delivery">
                    <label class="form-check-label" for="free-delivery">
                        Free delivery
                    </label>
                </div>
            </div>
        </form>
    </div>
</div> 
    `;
};

const updateTeamId = (e) => {
    data.selectedTeam = e.detail;
};

const handleResize = () => {
    if ($(window).width() < 768) {
      // Bootstrap's "md" breakpoint
      $("#collapseSort, #collapseFilters").removeClass("show");
    } else {
      $("#collapseSort, #collapseFilters").addClass("show");
    }
  };
  

const addEvents = () => {
    handleResize();
    document.addEventListener("dashboard:team-id", updateTeamId);
};

export default {
    data,
    template,
    addEvents,
};
