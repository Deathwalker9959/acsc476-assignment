import { emitTeamIdUpdated } from "../mixins/events.js";
import { data as sidebarData, createShop, addCategory, addHazard, addIngredient, addProduct, updateShopDeliveryPrice, updateShopName, updateShopPicture } from "../mixins/sidebar-actions.js"

let data = store({
    selectedTeam: null,
    dashboardActive: "active"
});

let template = () => {
    let { selectedTeam, dashboardActive } = data;

    return /*html*/ `
        <a id="branding" href="#" class="d-flex mx-auto align-items-center text-white text-decoration-none">
            <span class="fs-4">order.io</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a id="sidebarDashboard" href="#" class="nav-link text-white ${dashboardActive}">
                    <i class="bi bi-house me-2" width="16" height="16"></i>
                    Dashboard
                </a>
            </li>
            ${!selectedTeam ? sidebarGeneralActions() : ""}
            ${selectedTeam ? sidebarShopActions() : ""} 
        </ul>    
    `;
};

const sidebarGeneralActions = () => {
    return /*html*/ `
    <hr>
    <li class="nav-item mx-auto">
        <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#collapseShopActions" aria-expanded="true" aria-controls="collapseShopActions">
            <i class="bi bi bi-shop me-2" width="16" height="16"></i>
            Shop Actions
        </a>
    </li>
    <div class="collapse show" id="collapseShopActions">
        <li class="nav-item">
            <a id="createShop" href="#" class="nav-link text-white">
                <i class="bi bi-plus-circle me-2" width="16" height="16"></i>
                Create shop
            </a>
        </li>
    </div>
`;
}

const sidebarShopActions = () => {
    return /*html*/ `
        <hr>
        <li class="nav-item mx-auto">
            <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#collapseShopActions" aria-expanded="true" aria-controls="collapseShopActions">
                <i class="bi bi bi-shop me-2" width="16" height="16"></i>
                Shop Actions
            </a>
        </li>
        <div class="collapse show" id="collapseShopActions">
            <li class="nav-item">
                <a id="changeShopName" href="#" class="nav-link text-white">
                    <i class="bi bi-chat-square-quote me-2" width="16" height="16"></i>
                    Change shop name
                </a>
            </li>
            <li class="nav-item">
                <a id="changeShopPicture" href="#" class="nav-link text-white">
                    <i class="bi bi-image me-2" width="16" height="16"></i>
                    Change shop picture
                </a>
            </li>
            <li class="nav-item">
                <a id="changeDeliveryPrice" href="#" class="nav-link text-white">
                    <i class="fa fa-motorcycle me-2" width="16" height="16"></i>
                    Change delivery price
                </a>
            </li>
        </div>
        <li class="nav-item mx-auto">
        <a class="nav-link text-white dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#collapseShopProductActions" aria-expanded="true" aria-controls="collapseShopProductActions">
            <i class="bi bi bi-bag me-2" width="16" height="16"></i>
            Product Actions
        </a>
        </li>
        <div class="collapse show" id="collapseShopProductActions">
            <li class="nav-item">
            <a id="addCategory" href="#" class="nav-link text-white">
                <i class="bi bi-plus-circle me-2" width="16" height="16"></i>
                Add Category
            </a>
            </li>
            <li class="nav-item">
                <a id="addHazard" href="#" class="nav-link text-white">
                    <i class="bi bi-plus-circle me-2" width="16" height="16"></i>
                    Add Hazard
                </a>
            </li>
            <li class="nav-item">
                <a id="addIngredient" href="#" class="nav-link text-white">
                    <i class="bi bi-plus-circle me-2" width="16" height="16"></i>
                    Add Ingredient
                </a>
            </li>
            <li class="nav-item">
                <a id="addProduct" href="#" class="nav-link text-white">
                    <i class="bi bi-plus-circle me-2" width="16" height="16"></i>
                    Add Product
                </a>
            </li>
        </div>
    `;
};

const resetState = (e) => {
    emitTeamIdUpdated(null);
    e.preventDefault();
};

const updateTeamId = (e) => {
    data.selectedTeam = e.detail;
    sidebarData.selectedTeam = e.detail;
    if (e.detail != null) {
        data.dashboardActive = ""
    } else {
        data.dashboardActive = "active"
    }
};

const addEvents = () => {
    document.addEventListener("dashboard:team-id", updateTeamId);
    document.querySelector("#sidebarDashboard").addEventListener("click", resetState);
    if (!data.selectedTeam) {
        document.querySelector("#createShop").addEventListener("click", createShop);
    }
    if (!data.selectedTeam) return;
    document.querySelector("#changeShopName").addEventListener("click", updateShopName);
    document.querySelector("#changeShopPicture").addEventListener("click", updateShopPicture);
    document.querySelector("#changeDeliveryPrice").addEventListener("click", updateShopDeliveryPrice);
    document.querySelector("#addCategory").addEventListener("click", addCategory);
    document.querySelector("#addHazard").addEventListener("click", addHazard);
    document.querySelector("#addIngredient").addEventListener("click", addIngredient);
    document.querySelector("#addProduct").addEventListener("click", addProduct);
    document.querySelector("#branding").addEventListener("click", resetState);
};

export default {
    data,
    template,
    addEvents,
};
