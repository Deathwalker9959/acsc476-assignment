import { emitTeamIdUpdated } from "../mixins/events.js";

const teamDropdownItem = (teams) => {
    return teams
        .map(({ id, name }, index) => {
            return /*html*/ `
                <li>
                    <a class="${data.selectedTeam == id ? "active" : ""}" team-selector aria-id="${id}" href="#">${name}</a>
                </li>
        `;
        })
        .join(" ");
};

const selectedTeamTemplate = (teams) => {
    const selectedTeam = data.selectedTeam
        ? teams.find((team) => team.id == data.selectedTeam)
        : null;

    if (!selectedTeam) return "";

    return /*html*/ `
        <span class="mx-2">${selectedTeam.name}</span>
    `;
};

const teamsDropdownTemplate = (teams) => {
    return /*html*/ `
        <div class="dropdown">
            <a href="#" class="dropdown-button d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownTeams">
                ${selectedTeamTemplate(teams)}
                <i class="fa-solid fa-bars">&nbsp;</i>
            </a>
            <ul class="${data.dropdownVisible ? "d-block" : ""
        } dropdown-content dropdown-team-select text-small shadow px-3 my-3">
                <li>
                    <div class="input-and-submit-wrapper form-group my-2">
                        <div class="form-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" name="group-name" id="searchTeamText" autocomplete="off" placeholder="Search" class="has-icon form-control">
                    </div>
                </li>
                ${teamDropdownItem(teams)}
            </ul>
        </div>
    `;
};

let data = store({
    teams: [],
    filteredTeams: [],
    selectedTeam: null,
    dropdownVisible: false,
});

let template = () => {
    let { filteredTeams, teams } = data;

    return teamsDropdownTemplate(filteredTeams);
};

const updateTeamId = (e) => {
    data.selectedTeam = e.detail;
};

const addEvents = () => {
    document.addEventListener("dashboard:team-id", updateTeamId);

    /*
     * Remove event handlers on re-render
     */
    $("a[team-selector]").off("click");
    $("#searchTeamText").off("textInput input change");
    $("#dropdownTeams").off("click");
    $("#dropdownUser1").off('click');
    $(document).off("click");

    $("a[team-selector]").on("click", function (e) {
        // Remove the class from all elements with the team-selector attribute
        const teamId = $(this).attr("aria-id");
        emitTeamIdUpdated(teamId);
        e.stopImmediatePropagation();
    });

    $(document).on("click", function (event) {
        if (!$(event.target).closest(".dropdown").length) {
            data.dropdownVisible = false;
        }
    });

    $("#dropdownTeams").on("click", function (e) {
        data.dropdownVisible = !data.dropdownVisible;

        $('#dropdownUser1').removeClass('show');
        $('#dropdownUser1').attr('aria-expanded', false);
        $('#dropdownUser1 ~ ul').removeClass('show');

        e.stopImmediatePropagation();
    });

    $("#dropdownUser1").on('click', function(e) {
        data.dropdownVisible = false;
    });

    $("#searchTeamText").on("textInput input change", function (e) {
        const textVal = $(this).val().length > 0 ? $(this).val() : null;
        if (!textVal) {
            data.filteredTeams = data.teams;
            e.stopImmediatePropagation();
            return;
        }
        data.filteredTeams = data.teams.filter(({ name }) => {
            if (textVal) {
                return name.toLowerCase().includes(textVal.toLowerCase());
            }
        });
        e.stopImmediatePropagation();
    });
};

export default {
    data,
    template,
    addEvents,
};
