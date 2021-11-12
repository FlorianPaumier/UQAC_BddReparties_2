import React, {
    useEffect,
    useState
} from "react";

const SpellFilter = ({
                         queryParams,
                         setQueryParams,
                         filterSearch
                     }) => {

    const [showMenu, setShowMenu] = useState(false);
    const [schools, setSchools] = useState([])
    const [subSchools, setSubSchools] = useState([])
    const [form, setForm] = useState(queryParams)

    const addName = (value) => {
        form['name'] = value
        setForm(form)
    }

    const handleSchool = (e) => {
        const name = e.target.dataset.name;

        if (form['schools'] && form['schools'].indexOf(name) !== -1) {
            form['schools'].splice(form['schools'].indexOf(name), 1);
        } else {
            if (form["schools"]) {
                form['schools'].push(name)
            } else {
                form['schools'] = [name]
            }
        }

        setForm(form)
    }

    const handleSubSchool = (e) => {
        const name = e.target.dataset.name;
        if (form['subSchools'] && form['subSchools']?.indexOf(name) !== -1) {
            form['subSchools'].splice(form['subSchools'].indexOf(name), 1);
        } else {
            if (form["subSchools"]) {
                form['subSchools'].push(name)
            } else {
                form['subSchools'] = [name]
            }
        }
        setForm(form)
    }

    const getFilter = async () => {
        const req = await fetch("/api/spell/spells_filter");
        const json = await req.json()

        setSchools(json.schools)
        setSubSchools(json.subSchools)
    }

    useEffect(() => {
        getFilter()
    }, [])

    const submitForm = () => {
        setQueryParams(form)
    }

    if (schools.length === 0 && subSchools.length === 0) return <></>

    return <form
        id="search-form"
        onSubmit={submitForm}
        className="hidden xl:block mb-10 xl:mb-0 relative p-8 xl:p-0 w-full bg-gray-50 dark:bg-gray-900 xl:bg-white flex flex-col justify-start items-start">
        <div
            className="w-full xl:w-8/12 flex flex-col justify-start items-start space-y-4 pb-8 border-b border-gray-200">
            <div
                className="flex flex-col justify-start items-start space-y-4">
                <div
                    className="flex flex-col lg:mr-16 lg:py-0 py-4">
                    <label
                        htmlFor="email2"
                        className="text-gray-800 dark:text-gray-100 text-sm font-bold leading-tight tracking-normal mb-2">Nom</label>
                    <div
                        className="relative">
                        <div
                            className="absolute text-gray-600 dark:text-gray-400 flex items-center px-4 border-r dark:border-gray-700 h-full cursor-pointer">
                            <svg
                                focusable="false"
                                data-prefix="fas"
                                data-icon="search"
                                className="svg-inline--fa fa-search fa-w-16"
                                role="img"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512">
                                <path
                                    fill="currentColor"
                                    d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0
										208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9
										 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128
										  0 70.7-57.2 128-128 128z"/>
                            </svg>
                        </div>
                        <input
                            id="spell_name"
                            name="name"
                            defaultValue={form["name"]}
                            onChange={(e) => addName(e.target.value)}
                            className="text-gray-600 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 dark:text-gray-400 focus:outline-none
								         dark:focus:border-indigo-700 dark:border-gray-700 dark:bg-gray-800 bg-white font-normal w-64 h-10 flex items-center
								         pl-16 text-sm border-gray-300 rounded border shadow"
                            placeholder="Name"/>
                    </div>
                </div>
            </div>
        </div>
        <div
            id="schoolName"
            className="w-full xl:w-8/12 flex flex-col justify-start items-start space-y-4 pb-8 border-b border-gray-200">
            <div>
                <p className="text-base font-medium leading-4 text-gray-800 dark:text-white">
                    School</p>
            </div>
            <div
                className="flex flex-col justify-start items-start space-y-4">
                {schools.map(school => {
                    return <div
                        key={school.name}
                        className="flex flex-row justify-center items-center space-x-2">
                        <input
                            className="cursor-pointer border rounded border-gray-600"
                            type="checkbox"
                            onChange={handleSchool}
                            defaultChecked={form["schools"].indexOf(school.name) !== -1}
                            name={`school_${school.name}`}
                            data-name={school.name}
                            id={`school_${school.name}`}/>
                        <label
                            htmlFor={`school_${school.name}`}
                            className="text-base leading-4 text-gray-600  dark:text-white">
                            {school.name}</label>
                    </div>
                })}
            </div>
        </div>
        <div
            className="w-full xl:w-8/12 flex flex-col justify-start items-start space-y-4 py-8 border-b border-gray-200">
            <div>
                <p className="text-base font-medium leading-4 text-gray-800 dark:text-white">
                    SubSchool</p>
            </div>
            <div
                className="flex flex-col justify-start items-start space-y-4"
                id="subSchoolInput">
                {subSchools.map(subSchool => {
                    const name = subSchool.name !== '' ? subSchool.name : 'none';
                    return <div
                        key={name}
                        className="flex flex-row justify-center items-center space-x-2">
                        <input
                            className="cursor-pointer border rounded border-gray-600"
                            type="checkbox"
                            onChange={handleSubSchool}
                            defaultChecked={form["subSchools"].indexOf(name) !== -1}
                            name={`subSchool_${name}`}
                            data-name={name}
                            id={`subSchool_${name}`}/>
                        <label
                            htmlFor={`subSchool_${name}`}
                            className="text-base leading-4 text-gray-600 dark:text-white">
                            {name}</label>
                    </div>
                })}
            </div>
        </div>
        <div
            className="w-full xl:w-8/12 flex flex-col justify-start items-start space-y-4 py-8 border-b border-gray-200">
            <button
                id="form-search-btn"
                type="button"
                onClick={filterSearch}
                className="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:outline-none mx-2 my-2 bg-white transition duration-150
					 ease-in-out hover:border-indigo-600 hover:text-indigo-600 rounded border border-indigo-700 text-indigo-700 px-5 py-1 text-xs">
                Filtrer
            </button>
            <a href="/spell"
               className="focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:outline-none mx-2 my-2 bg-white transition duration-150
					 ease-in-out hover:border-indigo-600 hover:text-indigo-600 rounded border border-indigo-700 text-indigo-700 px-5 py-1 text-xs">Reset</a>
        </div>
        <div
            className="xl:hidden absolute top-6 right-6">
            <button
                onClick={() => setShowMenu(false)}>
                <svg
                    width="32"
                    height="32"
                    viewBox="0 0 32 32"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M24 8L8 24"
                        stroke="#1F2937"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"/>
                    <path
                        d="M8 8L24 24"
                        stroke="#1F2937"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"/>
                </svg>
            </button>
        </div>
    </form>
}

export default SpellFilter
