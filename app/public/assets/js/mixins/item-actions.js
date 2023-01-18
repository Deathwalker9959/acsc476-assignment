import { emitCartUpdated, emitProductsUpdated } from "../mixins/events.js";
import { createPopup } from "../mixins/popup-mixin.js";
import storage from "../mixins/storage.js";

let data = store({
    selectedTeam: null,
    products: null,
});

const editProduct = async (productId) => {
    let responseData = [];

    try {
        const url = `partner/api/shops/${data.selectedTeam}`;
        const headers = { "Content-Type": "application/json" };
        const response = await axios.get(url, "", { headers });
        responseData = response.data;
    } catch (e) {
        new swal("Error!", error, "error");
        throw e;
    }

    try {
        const reducedProducts = data.products.reduce(
            (accumulator, currentValue) => {
                accumulator[currentValue.id] = currentValue;
                return accumulator;
            },
            {}
        );

        const mappedData = {
            categories: [
                {
                    name: "Select a category",
                    value: "none",
                    selected: true,
                },
            ],
            hazards: [],
            ingredients: [],
        };

        Object.keys(mappedData).map((key) => {
            responseData[key].map((item) => {
                mappedData[key].push({
                    value: item.id,
                    name: item.name,
                });
            });
        });

        const selectedProduct = reducedProducts[productId];

        const reducedCategories = mappedData["categories"].reduce(
            (accumulator, currentValue) => {
                accumulator[currentValue.value] = currentValue;
                return accumulator;
            },
            {}
        );

        const reducedHazards = mappedData["hazards"].reduce(
            (accumulator, currentValue) => {
                accumulator[currentValue.value] = currentValue;
                return accumulator;
            },
            {}
        );

        const reducedIngredients = mappedData["ingredients"].reduce(
            (accumulator, currentValue) => {
                accumulator[currentValue.value] = currentValue;
                return accumulator;
            },
            {}
        );

        if (selectedProduct?.category?.category_id) {
            reducedCategories["none"].selected = false;
            reducedCategories[selectedProduct?.category?.category_id].selected = true;
        }

        selectedProduct?.hazards
            .filter(({ hazard_id }) => {
                return reducedHazards[hazard_id] ? true : false;
            })
            .forEach(({ hazard_id }) => {
                reducedHazards[hazard_id].selected = true;
            });

        selectedProduct?.ingredients
            .filter(({ ingredient_id }) => {
                return reducedIngredients[ingredient_id] ? true : false;
            })
            .forEach(({ ingredient_id }) => {
                reducedIngredients[ingredient_id].selected = true;
            });

        const attributes = [
            {
                type: "title",
                placeholder: "Edit Product",
            },
            {
                id: "name",
                type: "text",
                placeholder: "Item Name",
                defaultValue: selectedProduct?.name,
                required: true,
            },
            {
                id: "description",
                type: "text",
                placeholder: "Item Description",
                defaultValue: selectedProduct?.description,
            },
            {
                id: "price",
                type: "number",
                placeholder: "Item Price",
                defaultValue: selectedProduct?.price,
                required: true,
            },
            {
                type: "label",
                text: "Item Image",
            },
            {
                id: "photo",
                type: "file",
                placeholder: "Item Image",
            },
            {
                type: "label",
                text: "Categories",
            },
            {
                id: "category",
                type: "select",
                options: mappedData.categories,
                placeholder: "Select a category",
            },
            {
                type: "label",
                text: "Hazards",
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
                text: "Ingredients",
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

        if (!results) return;

        const url = `partner/api/shops/${data.selectedTeam}/products/${productId}`;
        const headers = { "Content-Type": "application/json" };
        const response = await axios.put(url, { attributes: results }, { headers });
        new swal("Success", "Product added successfully", "success");
        emitProductsUpdated();
        return response.data;
    } catch (error) {
        new swal("Error!", error, "error");
        throw error;
    }
};

const removeProduct = async (productId) => {
    try {
        const result = await new swal({
            title: "Remove product",
            text: "Are you sure you want to remove this product?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonText: "Cancel",
            cancelButtonColor: "#d33",
        });

        if (result.value) {
            const url = `partner/api/shops/${data.selectedTeam}/products/${productId}`;
            const headers = { "Content-Type": "application/json" };
            const response = await axios.delete(url, "", { headers });
            new swal("Success", "Product removed successfully", "success");
            emitProductsUpdated();
            return response.data;
        }
    } catch (error) {
        await new swal("Error!", error, "error");
        throw error;
    }
};

const presentProductDetails = async (productId) => {
    let responseData = [];

    try {
        const url = `api/shops/${data.selectedTeam}/products`;
        const headers = { "Content-Type": "application/json" };
        const response = await axios.get(url, "", { headers });
        responseData = response.data;
    } catch (e) {
        throw e;
    }

    const product = data.products.find((product) => product.id == productId);

    const mappedData = {
        ingredients: [],
        hazards: [],
    };

    Object.keys(mappedData).map((key) => {
        responseData.map((item) => {
            if (item[key].length > 0)
                item[key].map((child) => {
                    if (!mappedData[key][child.product_id])
                        mappedData[key][child.product_id] = [];
                    mappedData[key][child.product_id].push({
                        name: `${child.name}${child.price
                            ? ` &euro; ${parseInt(child.price) == 0 ? "Free" : child.price}`
                            : ""
                            }`,
                        value: child.id,
                    });
                });
        });
    });

    const ingredients = mappedData.ingredients[productId] ?? [];

    const hazardNames = mappedData.hazards[productId]?.reduce((acc, obj) => {
        acc.push(obj.name);
        return acc;
    }, []);

    const attributes = [
        {
            type: "title",
            placeholder: "Product Details",
        },
        {
            type: "showCancelButton",
            text: "Cancel",
            color: "#dc3545",
        },
        {
            type: "confirmButtonText",
            text: "Add To Cart",
            color: "#198754",
        },
        {
            type: "label",
            text: `Name: ${product.name}`,
        },
        {
            id: "id",
            type: "number",
            required: true,
            hidden: true,
            defaultValue: productId,
        },
        {
            id: "name",
            type: "text",
            required: true,
            hidden: true,
            defaultValue: product.name,
        },
        {
            type: "image",
            src: product.photo_url,
            alt: "Product Image",
            width: "200px",
            height: "200px",
        },
        {
            type: "label",
            text: "Select Ingredients:",
        },
        {
            id: "ingredients",
            type: "select",
            options: ingredients,
            multiple: true,
            placeholder: "Select ingredients",
        },
        {
            type: "label",
            text: "Product Quantity",
        },
        {
            id: "quantity",
            type: "number",
            required: true,
            defaultValue: 1,
            placeholder: "Product Quantity",
        },
    ];

    if (hazardNames)
        attributes.concat([
            {
                type: "label",
                text: "Hazards:",
            },
            {
                type: "label",
                text: `May include: ${hazardNames.join(",")}`,
            },
        ]);

    const results = await createPopup(attributes);

    if (!results) {
        return;
    }

    if (storage.get(`shops_${data.selectedTeam}_products`) == null)
        storage.set(`shops_${data.selectedTeam}_products`, []);

    storage.append(`shops_${data.selectedTeam}_products`, results);

    new swal("Success", "Product added successfully", "success");
    emitCartUpdated();
    return results;
};

export { data, editProduct, removeProduct, presentProductDetails };
