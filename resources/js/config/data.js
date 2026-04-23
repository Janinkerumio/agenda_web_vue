export const dashboardData = {
    recentConcerns: [
        {
            name: "Carlos Sainz",
            concernType: "Open Concern",
            status: "active",
            dateRaised: "May 15, 2025",
            imageUrl: "http://static.photos/people/200x200/2",
        },
        {
            name: "Lewis Hamilton",
            concernType: "Open Concern",
            status: "pending",
            dateRaised: "June 20, 2025",
            imageUrl: "http://static.photos/people/200x200/5",
        },
        {
            name: "George Russells",
            concernType: "Closed Concern",
            status: "inReview",
            dateRaised: "Apr 30, 2025",
            imageUrl: "http://static.photos/people/200x200/8",
        },
    ],
    statusValues: {
        upcAgenda: 80,
        openConcerns: 42,
        closedConcerns: 57,
        completionPerc: 76,
        totalEntries: 143,
    },
    recentActivites: [
        {
            type: "New Concern",
            activity: "Open concern from John Smith",
            activity_date: "2 minutes ago",
            tIcon: "nConcern",
        },
        {
            type: "New User",
            activity: "Sarah Johnson registered",
            activity_date: "15 minutes ago",
            tIcon: "nUser",
        },
        {
            type: "New comment",
            activity: "On Dashboard Design",
            activity_date: "1 hour ago",
            tIcon: "nComment",
        },
    ],
    latestAgenda: {
        agenda_title: "Project Update Meeting",
        date: "Nov. 15, 2025",
        notes: "Reviewed progress on Q4 deliverables. Identified blockers and reassigned tasks.",
    },
};