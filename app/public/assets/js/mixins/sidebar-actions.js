import { createPopup } from "../mixins/popup-mixin.js";
import { emitShopsUpdated, emitProductsUpdated } from "../mixins/events.js";

let data = store({
    selectedTeam: null,
});

const addCategory = async () => {

    const attributes = [
        {
            type: "title",
            placeholder: "Add Category",
        },
        {
            id: "name",
            type: "text",
            placeholder: "Category Name",
            required: true,
        }
    ];

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    try {
        const url = `partner/api/shops/${data.selectedTeam}/categories`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.post(url, results, { headers });
        new swal('Success', 'Category added successfully', 'success');
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }
};

const addHazard = async () => {

    const attributes = [
        {
            type: "title",
            placeholder: "Add Hazard",
        },
        {
            id: "name",
            type: "text",
            placeholder: "Hazard Name",
            required: true,
        },
        {
            id: "description",
            type: "text",
            placeholder: "Hazard Description",
        }
    ];

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    try {
        const url = `partner/api/shops/${data.selectedTeam}/hazards`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.post(url, results, { headers });
        new swal('Success', 'Hazard added successfully', 'success');
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }
};

const addIngredient = async () => {

    const attributes = [
        {
            type: "title",
            placeholder: "Add Ingredient",
        },
        {
            id: "name",
            type: "text",
            placeholder: "Ingredient Name",
            required: true,
        },
        {
            id: "description",
            type: "text",
            placeholder: "Ingredient Description",
        },
        {
            id: "price",
            type: "number",
            placeholder: "Ingredient Price",
        }
    ];

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    try {
        const url = `partner/api/shops/${data.selectedTeam}/products/ingredients`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.post(url, results, { headers });
        new swal('Success', 'Ingredient added successfully', 'success');
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }

};

const addProduct = async () => {

    let responseData = [];

    try {
        const url = `partner/api/shops/${data.selectedTeam}`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.get(url, "", { headers });
        responseData = response.data;
    } catch (e) {
        throw e;
    }

    try {

        const mappedData = {
            categories: [
                {
                    name: "Select a category",
                    value: "none",
                    selected: true,
                }
            ],
            hazards: [],
            ingredients: [],
        };

        Object.keys(mappedData).map((key) => {
            responseData[key].map((item) => {
                mappedData[key].push({
                    value: item.id,
                    name: item.name,
                })
            })
        });

        const attributes = [
            {
                type: "title",
                placeholder: "Add Product",
            },
            {
                id: "name",
                type: "text",
                placeholder: "Item Name *",
                required: true,
            },
            {
                id: "description",
                type: "text",
                placeholder: "Item Description",
            },
            {
                id: "price",
                type: "number",
                placeholder: "Item Price *",
                required: true,
            },
            {
                type: "label",
                text: "Item Image"
            },
            {
                id: "photo",
                type: "file",
                placeholder: "Item Image",
            },
            {
                type: "label",
                text: "Categories"
            },
            {
                id: "category",
                type: "select",
                options: mappedData.categories,
                placeholder: "Select a category",
            },
            {
                type: "label",
                text: "Hazards"
            },
            {
                id: "hazards",
                type: "select",
                options: mappedData.hazards,
                multiple: true,
                placeholder: "Select hazards",
            },
            {
                type: "label",
                text: "Ingredients"
            },
            {
                id: "ingredients",
                type: "select",
                options: mappedData.ingredients,
                multiple: true,
                placeholder: "Select ingredients",
            },
        ];

        const results = await createPopup(attributes);

        if (!results)
            return;

        const url = `partner/api/shops/${data.selectedTeam}/products`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.post(url, results, { headers });
        new swal('Success', 'Product added successfully', 'success');
        emitProductsUpdated();
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }
};

const createShop = async () => {
    const attributes = [
        {
            type: "title",
            placeholder: "Create Shop",
        },
        {
            id: "name",
            type: "text",
            placeholder: "Shop Name",
            required: true,
        },
        {
            id: "delivery_price",
            type: "number",
            placeholder: "Delivery Price",
        },
        {
            type: "label",
            text: "Shop Image"
        },
        {
            id: "photo",
            type: "file",
            placeholder: "Item Image",
        },
    ];

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    try {
        const url = `partner/api/shops`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.post(url, results, { headers });
        new swal('Success', 'Shop created successfully', 'success');
        emitShopsUpdated();
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }
}

const updateShopName = async () => {
    const attributes = [
        {
            type: "title",
            placeholder: "Update Name",
        },
        {
            id: "name",
            type: "text",
            placeholder: "Shop Name",
            required: true,
        },
    ];

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    try {
        const url = `partner/api/shops/${data.selectedTeam}`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.put(url, results, { headers });
        new swal('Success', 'Name updated successfully', 'success');
        emitShopsUpdated();
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }
}

const updateShopPicture = async () => {
    const attributes = [
        {
            type: "title",
            placeholder: "Update Picture",
        },
        {
            type: "label",
            text: "Shop Image"
        },
        {
            id: "photo",
            type: "file",
            placeholder: "Shop Image",
            required: true,
        },
    ];

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    try {
        const url = `partner/api/shops/${data.selectedTeam}`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.put(url, results, { headers });
        new swal('Success', 'Picture updated successfully', 'success');
        emitShopsUpdated();
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }
}

const updateShopDeliveryPrice = async () => {
    const attributes = [
        {
            type: "title",
            placeholder: "Update Delivery Price",
        },
        {
            id: "delivery_price",
            type: "number",
            placeholder: "Delivery Price",
            required: true,
        },
    ];

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    try {
        const url = `partner/api/shops/${data.selectedTeam}`
        const headers = { 'Content-Type': 'application/json' }
        const response = await axios.put(url, results, { headers });
        new swal('Success', 'Delivery price updated successfully', 'success');
        emitShopsUpdated();
        return response.data;
    } catch (error) {
        new swal('Error!', error.response.data ?? "error", 'error');
        throw error;
    }
}

export {
    addCategory,
    addHazard,
    addIngredient,
    addProduct,
    createShop,
    updateShopName,
    updateShopPicture,
    updateShopDeliveryPrice,
    data,
}