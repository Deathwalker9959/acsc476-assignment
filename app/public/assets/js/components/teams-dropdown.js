const teamDropdownItem = (teams) => {
    return teams.map(({ name }, index) => {
        return /*html*/`
        <li><a class="dropdown-item" team-selector key="${index}" href="#">${name}</a></li>
        `
    }).join(' ');
}

const teamsDropdownTemplate = (teams) => {
    return /*html*/`
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-bars">&nbsp;</i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark dropdown-team-select text-small shadow px-3 my-3">
            <li>
                <div class="input-and-submit-wrapper form-group my-2">
                    <div class="form-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="group-name" id="group-name" autocomplete="off" placeholder="Search" class="has-icon form-control">
                </div>
            </li>
            ${teamDropdownItem(teams)}
        </ul>
    `
}

let data = store({
    teams: [
    ],
});

let template = () => {
    let { teams } = data;

    return teamsDropdownTemplate(teams);
}

const addEvents = () => {
    $('[team-selector]').on('click', function () {
        // Remove the class from all elements with the team-selector attribute
        $('[team-selector]').removeClass('active');

        // Add the class to the clicked element
        $(this).addClass('active');
    });
}

export default {
    data,
    template,
    addEvents,
};
