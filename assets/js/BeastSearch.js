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

const BeastSearch = () => {

    const [queryParams, setQueryParams] = useState({
        page: 1,
        types: [],
        subTypes: [],
        cr: [],
        name: '',
        start_with: ''
    });

    const [beasts, setBeasts] = useState([])
    const [waiting, setWaiting] = useState(true)
    const [pagination, setPagination] = useState({})

    const generateQuery = () => {
        return `?page=${queryParams["page"]}&types=${queryParams["types"].join(',')}&subTypes=${queryParams["subTypes"].join(',')}&cr=${queryParams["cr"].join(',')}&name=${queryParams["name"]}&start_with=${queryParams["start_with"]}`
    }

    const getBeasts = async () => {
        console.log(queryParams)
        setWaiting(true)
        const req = await fetch(`/api/beast${generateQuery()}`)
        const json = await req.json()
        setBeasts(json.pagination.items);
        setPagination({
            current_page_number: json.pagination.current_page_number,
            num_items_per_page: json.pagination.num_items_per_page,
            total_count: json.pagination.total_count,
            total_pages: json.pagination.total_pages,
        })
        setWaiting(false)
    }

    useEffect(() => {
        getBeasts()
    }, [queryParams]);

    let changePage = (page) => {
        queryParams["page"] = page
        setQueryParams({...queryParams})
    };

    return <section>
        <Loader show={waiting}/>
        <section className={`${waiting ? 'hidden' : ''}`}>
            <PageHeader
                params={queryParams}
                setParams={setQueryParams}/>
            <div
                className="mx-auto container py-12 px-4">
                <div
                    className="flex flex-col w-full xl:flex-row justify-center">
                    {beasts.length > 1 && (
                        <>
                            <BeastFilter
                                queryParams={queryParams}
                                setQueryParams={setQueryParams}
                                filterSearch={getBeasts}/>

                            <section>
                                <div
                                    className="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 lg:gap-y-6 lg:gap-x-4 sm:gap-y-10 sm:gap-x-3 gap-y-6 lg:mt-12 mt-10 mb-4">
                                    {beasts.map(beast =>
                                        <BeastCard
                                            monster={beast}/>)}
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
    <BeastSearch/>, document.getElementById('beastSearchRoot'));
