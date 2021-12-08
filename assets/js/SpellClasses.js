import ReactDOM
    from "react-dom";
import PageHeader
    from "./components/PageHeader";
import BeastFilter
    from "./components/Beast/BeastFilter";
import BeastCard
    from "./components/Beast/BeastCard";
import Pagination
    from "./components/Pagination";
import React, {
    useEffect,
    useState
} from "react";
import Loader
    from "./components/Loader";

const SpellClasses = () => {

    const [queryParams, setQueryParams] = useState({
        page: 1,
        start_with: ''
    });

    const [spells, setSpells] = useState([])
    const [waiting, setWaiting] = useState(true)
    const [pagination, setPagination] = useState({})

    Object.filter = (obj, predicate) =>
        Object.keys(obj)
            .filter( key => predicate(obj[key]) )
            .reduce( (res, key) => (res[key] = obj[key], res), {} );

    const generateQuery = () => {
        return `?page=${queryParams["page"]}&start_with=${queryParams["start_with"]}`
    }

    const getSpells = async () => {
        setWaiting(true)
        const req = await fetch(`/api/search/spell-class${generateQuery()}`)
        const json = await req.json()
        setSpells(json.pagination.items);
        setPagination({
            current_page_number: json.pagination.current_page_number,
            num_items_per_page: json.pagination.num_items_per_page,
            total_count: json.pagination.total_count,
            total_pages: json.pagination.total_pages,
        })

        setWaiting(false)
    }

    useEffect(() => {
        getSpells()
    }, [queryParams]);

    let changePage = (page) => {
        queryParams["page"] = page
        setQueryParams({...queryParams})
    };

    return <section>
        <Loader
            show={waiting}/>
        <section
            className={`${waiting ? 'hidden' : ''}`}>
            <PageHeader
                params={queryParams}
                setParams={setQueryParams}/>
            <div
                className="mx-auto container py-12 px-4">
                <div
                    className="flex flex-col w-full xl:flex-row justify-center">
                    {Object.keys(spells).length > 1 && (
                        <>
                            <section>
                                <div
                                    className="grid lg:grid-cols-4 sm:grid-cols-3 grid-cols-1 lg:gap-y-6 lg:gap-x-4 sm:gap-y-10 sm:gap-x-3 gap-y-6 lg:mt-12 mt-10 mb-4">
                                    {Object.values(spells).map(spell => {
                                        return <div
                                            className="bg-white dark:bg-gray-800 shadow xl:flex lg:flex md:flex p-5 rounded">
                                            <div
                                                className="w-full">
                                                <a tabIndex="0" href={`/spell/${spell.spell.id}`}
                                                   className="focus:outline-none text-gray-800 dark:text-gray-100">
                                                    <p className="text-lg  mb-3 font-normal">
                                                        <u>{spell.spell.name}</u>
                                                    </p>
                                                </a>
                                                <p tabIndex="0"
                                                   className="focus:outline-none text-sm text-gray-600 dark:text-gray-400 font-normal
                                                   grid grid-cols-2">
                                                    {spell.types.map(type => {
                                                        return <a
                                                            className="text-blue-500 underline background-transparent font-bold
                                                                 text-xs outline-none focus:outline-none ease-linear transition-all duration-150">
                                                            {type.name}
                                                        </a>
                                                    })}
                                                </p>
                                            </div>
                                        </div>
                                    })
                                    }
                                </div>
                                <div
                                    className="flex justify-center items-center">
                                    <Pagination
                                        changePage={changePage}
                                        count={pagination.total_count}
                                        currentPage={pagination.current_page_number}
                                        total={pagination.total_pages}
                                        nbParPage={pagination.num_items_per_page}/>
                                </div>
                            </section>
                        </>
                    )}
                </div>
            </div>
        </section>
    </section>;
}


ReactDOM.render(
    <SpellClasses/>, document.getElementById('spellsClasses'));
