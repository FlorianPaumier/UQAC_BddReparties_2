import ReactDOM
    from "react-dom";
import React, {
    useEffect,
    useState
} from "react";
import {
    Graph
} from "react-d3-graph";
import {
    ForceGraph2D,
    ForceGraph3D
} from "react-force-graph";

const SpellGraph = () => {

    const [data, setData] = useState({
        nodes: [],
        links: []
    });

    // const myConfig = {
    //     nodeHighlightBehavior: true,
    //     staticGraph: true,
    //     gravity: -50,
    //     height: '75vh',
    //     // initialZoom: 1,
    //     panAndZoom: true,
    //     node: {
    //         color: "lightgreen",
    //         size: 120,
    //         highlightStrokeColor: "blue",
    //     },
    //     link: {
    //         highlightColor: "lightblue",
    //     },
    // };
    const width = window.innerWidth;
    const height = window.innerHeight;

    const myConfig = {
        nodeHighlightBehavior: true,
        width: width,
        height: height,
        gravity: -100,
        node: {
            color: "lightgreen",
            size: 120,
            highlightStrokeColor: "blue",
        },
        link: {
            highlightColor: "lightblue",
        },
    };
    const getData = async () => {
        const req = await fetch("/api/spell/graph")
        const json = await req.json();
        setData(json.data)
    }

    useEffect(() => {
        getData()
    }, []);


    if (data.nodes.length === 0) return <></>

    return <ForceGraph3D
        graphData={data}
        nodeLabel={node => `${node.id}`}
        nodeAutoColorBy="group"
        height={height}
        // showNavInfo={true}
        // linkDirectionalParticles={1}
    />
}

ReactDOM.render(
    <SpellGraph/>, document.getElementById('graphRoot'));
