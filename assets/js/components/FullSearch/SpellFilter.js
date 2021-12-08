import React
    , {
    useEffect,
    useState
} from "react";
import MultiSelect
    from "../MultiSelect";
import Toggle
    from "../Toggle";

const SpellFilter = ({
                         params,
                         setItems,
                         open
                     }) => {

    const [schools, setSchools] = useState([])
    const [subSchools, setSubSchools] = useState([])
    const [targets, setTargets] = useState([])
    const [domains, setDomains] = useState([])
    const [effects, setEffects] = useState([])
    const [duration, setDuration] = useState([])
    const [castingTime, setCastingTime] = useState([])

    const [selectedSchools, setSelectedSchools] = useState([])
    const [selectedSubSchools, setSelectedSubSchools] = useState([])
    const [selectedTargets, setSelectedTargets] = useState([])
    const [selectedDomains, setSelectedDomains] = useState([])
    const [selectedEffects, setSelectedEffects] = useState([])
    const [selectedDuration, setSelectedDuration] = useState([])
    const [selectedCastingTime, setSelectedCastingTime] = useState([])
    const [form, setForm] = useState({
        'schools': [],
        'subSchools': [],
        'domains': [],
        'effects': [],
        'duration': [],
        'targets': [],
        'castingTime': [],
        'level': {min: 0, max: null},
        'name': "",
        'description': '',
        'fullText': '',
        'components': [],
        'focus': []
    });
    const components = {
        'v': {
            text: "V",
            name: "components",
            value: 'v',
            open: false
        },
        's': {
            name: "components",
            text: "S",
            value: 's',
            open: false
        },
        'm': {
            text: "M",
            name: "components",
            value: 'm',
            open: false
        }
    }
    const focus = {
        'arcane' : {
            name: 'arcane-focus',
            value: 'arcane',
            text: 'Arcane Focus',
            open: false
        },
        'divine': {
            name: 'divine-focus',
            value: 'divine',
            text: 'Divine Focus',
            open: false
        }
    }

    const selectComponent = (index) => {
        components[index].open = !components[index].open
        form.components = components
    }
    const selectFocus = (index) => {
        focus[index].open = !focus[index].open
        form.focus = focus
    }
    const handleSchool = (data) => {
        form["schools"] = data.map(item => item)
        setForm(form)
    }
    const handleSubSchool = (data) => {
        form["subSchools"] = data.map(item => item)
        setForm(form)
    }
    const handleDomains = (data) => {
        form["domains"] = data.map(item => item)
        setForm(form)
    }
    const handleEffects = (data) => {
        form["effects"] = data.map(item => item)
        setForm(form)
    }
    const handleDuration = (data) => {
        form["duration"] = data.map(item => item)
        setForm(form)
    }
    const handleCastingTime = (data) => {
        form["castingTime"] = data.map(item => item)
        setForm(form)
    }
    const handleTargets = (data) => {
        form["targets"] = data.map(item => item)
        setForm(form)
    }
    const getFilter = async () => {
        const req = await fetch("/api/search/spell-filter");
        const json = await req.json()
        setSchools(json.schools)
        setSubSchools(json.subSchools)
        setDomains(Object.values(json.domains))
        setEffects(json.effects)
        setDuration(json.duration)
        setTargets(json.targets)
        setCastingTime(json.castingTime)
    }
    const handleLevel = (e) => {
        form['level'][e.target.dataset.key] = e.target.value;
        setForm({...form})
    }
    const handleFullText = (e) => {
        form.fullText = e.target.value;
        setForm({...form})
    }

    const applyFilter = async () => {
        setItems([])
        const req = await fetch("/api/search/spells", {
            method: 'POST',
            body: JSON.stringify(form)
        })
        const json = await req.json()
        setItems(Object.values(json.pagination))
    }

    useEffect(() => {
        getFilter()
    }, [])

    if (!open) return <></>

    return <section
        id={'spellFilter'}
        className={"grid grid-cols-4 gap-x-4 gap-y-4 px-4"}>
        <label
            className={"w-full"}>Schools
            <MultiSelect
                selectItems={selectedSchools}
                itemsList={schools}
                setParams={handleSchool}/></label>
        <label
            className={"w-full"}>SubSchools <MultiSelect
            selectItems={selectedSubSchools}
            itemsList={subSchools}
            setParams={handleSubSchool}/></label>
        <label
            className={"w-full"}>Domains <MultiSelect
            selectItems={selectedDomains}
            itemsList={domains}
            setParams={handleDomains}/></label>
        <label
            className={"w-full"}>Effects <MultiSelect
            selectItems={selectedEffects}
            itemsList={effects}
            setParams={handleEffects}/></label>
        <label
            className={"w-full"}>Duration <MultiSelect
            selectItems={selectedDuration}
            itemsList={duration}
            setParams={handleDuration}/></label>
        <label
            className={"w-full"}>Casting
            Time <MultiSelect
                selectItems={selectedCastingTime}
                itemsList={castingTime}
                setParams={handleCastingTime}/></label>
        <label
            className={"w-full"}>Targets <MultiSelect
            selectItems={selectedTargets}
            itemsList={targets}
            setParams={handleTargets}/></label>
        <section>
            Level
            : <br/>
            <label
                htmlFor="min-level"
                className={"mr-4"}>Min
                <input
                    tabIndex="0"
                    type="number"
                    id="min-level"
                    name="min-level"
                    data-key={"min"}
                    min={0}
                    onChange={handleLevel}
                    className="w-1/4 md:w-1/6 border border-gray-300 dark:border-gray-700 pl-3 py-3 shadow-sm rounded text-sm focus:outline-none focus:border-indigo-700 bg-transparent placeholder-gray-500 text-gray-600 dark:text-gray-400"
                /></label>
            <label
                htmlFor="max-level">Max<input
                tabIndex="0"
                type="number"
                id="max-level"
                name="max-level"
                min={form.level.min}
                data-key={"max"}
                onChange={handleLevel}
                required
                className="w-1/4 md:w-1/6 border border-gray-300 dark:border-gray-700 pl-3 py-3 shadow-sm rounded text-sm focus:outline-none focus:border-indigo-700 bg-transparent placeholder-gray-500 text-gray-600 dark:text-gray-400"
            /></label>
        </section>
        <label
            htmlFor="spell-name">Spell
            Name<input
                tabIndex="0"
                type="text"
                id="spell-name"
                name="spell-name"
                min={0}
                required
                className="w-full border border-gray-300 dark:border-gray-700 pl-3 py-3 shadow-sm rounded text-sm focus:outline-none focus:border-indigo-700 bg-transparent placeholder-gray-500 text-gray-600 dark:text-gray-400"
            /></label>
        <label
            htmlFor="description">Description <input
            tabIndex="0"
            type="text"
            id="description"
            name="description"
            required
            className="w-full border border-gray-300 dark:border-gray-700 pl-3 py-3 shadow-sm rounded text-sm focus:outline-none focus:border-indigo-700 bg-transparent placeholder-gray-500 text-gray-600 dark:text-gray-400"
        /></label>
        <label
            htmlFor="spell-text">Full Text<input
                tabIndex="0"
                type="text"
                id="spell-text"
                name="spell-text"
                onChange={handleFullText}
                className="w-full border border-gray-300 dark:border-gray-700 pl-3 py-3 shadow-sm rounded text-sm focus:outline-none focus:border-indigo-700 bg-transparent placeholder-gray-500 text-gray-600 dark:text-gray-400"
            /></label>
        <section className={"pt-4"}>
            <span
                className="mr-4">V,S,M</span>
            {Object.values(components).map(component =>
                <Toggle
                    type={component.type}
                    text={component.text}
                    value={component.value}
                    open={component.open}
                    name={component.name}
                    onChange={selectComponent}/>)}
        </section>
        <section>
            <span
                className="mr-4">Focus</span>
            {Object.values(focus).map(item =>
                <Toggle
                    text={item.text}
                    value={item.value}
                    open={item.open}
                    name={item.name}
                    onChange={selectFocus}/>)}
        </section>
        <button className={"w-1/2 h-10 px-5 text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-full focus:shadow-outline hover:bg-indigo-800"} onClick={applyFilter}>Valider</button>
    </section>
}

export default SpellFilter
