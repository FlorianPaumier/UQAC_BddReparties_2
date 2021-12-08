import React, {
    useEffect,
    useState
} from 'react';
import ReactDOM
    from 'react-dom';
import PageHeader
    from "./components/PageHeader";
import SpellFilter
    from "./components/Spell/SpellFilter";
import SpellCard
    from "./components/Spell/SpellCard";
import Pagination
    from "./components/Pagination";

const SpellSearch = () => {

    const [queryParams, setQueryParams] = useState({
        page: 1,
        schools: [],
        subSchools: [],
        name: '',
        start_with: ''
    });
    const [spells, setSpells] = useState([])
    const [pagination, setPagination] = useState({})

    const generateQuery = () => {
        return `?page=${queryParams["page"]}&schools=${queryParams["schools"]?.join(',')}&subSchools=${queryParams["subSchools"]?.join(',')}&name=${queryParams["name"]}&start_with=${queryParams["start_with"]}`
    }

    const getSpells = async () => {

        const req = await fetch(`/api/spell${generateQuery()}`)
        const json = await req.json()
        setSpells(json.pagination.items);
        setPagination({
            current_page_number: json.pagination.current_page_number,
            num_items_per_page: json.pagination.num_items_per_page,
            total_count: json.pagination.total_count,
            total_pages: json.pagination.total_pages,
        })
    }

    useEffect(() => {
        getSpells()
    }, [queryParams]);

    let changePage = (page) => {
        queryParams["page"] = page
        setQueryParams({...queryParams})
    };

    return <section>
        <PageHeader
            params={queryParams}
            setParams={setQueryParams}/>
        <div
            className="mx-auto container py-12 px-4">
            <div
                className="flex flex-col w-full xl:flex-row justify-center">

                {spells.length === 0 && (
                    <span
                        className={"text-2xl"}>Waiting</span>)}

                {spells.length > 1 && (
                    <>
                        <SpellFilter
                            queryParams={queryParams}
                            setQueryParams={setQueryParams}
                            filterSearch={getSpells}/>
                        <section>
                            <div
                                className="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 lg:gap-y-12 lg:gap-x-8 sm:gap-y-10 sm:gap-x-6 gap-y-6 lg:mt-12 mt-10 mb-4">
                                {spells.map(spell =>
                                    <SpellCard
                                        spell={spell}/>)}
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
    </section>;
}

ReactDOM.render(
    <SpellSearch/>, document.getElementById('spellSearchRoot'));
