import { Button } from "@/components/ui/button"
import { Card } from "@/components/ui/card"
import { Check, X, ArrowRight } from "lucide-react"

export default function PricingPage() {
  return (
    <div className="min-h-screen bg-slate-900 text-white">
      {/* Header */}
      <div className="text-center pt-12 pb-8">
        <p className="text-sm font-medium tracking-wider text-slate-300 uppercase">PRICES</p>
      </div>

      {/* Price per day section */}
      <div className="max-w-4xl mx-auto px-6 mb-16">
        <div className="text-center mb-12">
          <h1 className="text-4xl md:text-5xl font-bold mb-4">Price per day</h1>
          <p className="text-slate-400 text-lg">For small projects or an extra day</p>
        </div>

        <div className="grid md:grid-cols-2 gap-6 max-w-2xl mx-auto">
          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl">
            <h3 className="text-xl font-semibold mb-4 text-white">Weekday</h3>
            <div className="flex items-center justify-between bg-white rounded-full p-2">
              <span className="text-slate-900 font-semibold px-4">€549/day</span>
              <Button size="sm" className="rounded-full bg-slate-900 hover:bg-slate-800">
                <ArrowRight className="w-4 h-4" />
              </Button>
            </div>
          </Card>

          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl">
            <h3 className="text-xl font-semibold mb-4 text-white">Weekend</h3>
            <div className="flex items-center justify-between bg-white rounded-full p-2">
              <span className="text-slate-900 font-semibold px-4">€649/day</span>
              <Button size="sm" className="rounded-full bg-slate-900 hover:bg-slate-800">
                <ArrowRight className="w-4 h-4" />
              </Button>
            </div>
          </Card>
        </div>
      </div>

      {/* Our Packs section */}
      <div className="max-w-6xl mx-auto px-6 mb-16">
        <div className="text-center mb-12">
          <h2 className="text-4xl md:text-5xl font-bold mb-4">Our Packs</h2>
          <p className="text-slate-400 text-lg">Save and get extra days</p>
        </div>

        <div className="grid md:grid-cols-3 gap-6">
          {/* 5 Days Pack */}
          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl relative">
            <div className="absolute top-4 right-4">
              <span className="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">€2,595</span>
            </div>
            <div className="mb-6">
              <h3 className="text-3xl font-bold mb-2 text-white">5 Days</h3>
              <p className="text-slate-400 mb-1">€519/day</p>
              <p className="text-slate-500 text-sm">Valid for 1 year</p>
            </div>

            <div className="space-y-3 mb-8">
              <div className="flex items-center gap-3">
                <Check className="w-5 h-5 text-blue-400" />
                <span className="text-white">5% Discount</span>
              </div>
              <div className="flex items-center gap-3">
                <X className="w-5 h-5 text-slate-500" />
                <span className="text-slate-500">Gift Box</span>
              </div>
              <div className="flex items-center gap-3">
                <X className="w-5 h-5 text-slate-500" />
                <span className="text-slate-500">Projects Files</span>
              </div>
              <div className="flex items-center gap-3">
                <X className="w-5 h-5 text-slate-500" />
                <span className="text-slate-500">Weekends Included</span>
              </div>
            </div>

            <Button className="w-full bg-white text-slate-900 hover:bg-slate-100 rounded-full font-semibold">
              Get 5 Days
              <ArrowRight className="w-4 h-4 ml-2" />
            </Button>
          </Card>

          {/* 10 Days Pack */}
          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl relative">
            <div className="absolute top-4 right-4">
              <span className="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">€4,990</span>
            </div>
            <div className="mb-6">
              <h3 className="text-3xl font-bold mb-2 text-white">10 Days</h3>
              <p className="text-slate-400 mb-1">€499/day</p>
              <p className="text-slate-500 text-sm">Valid for 3 years</p>
            </div>

            <div className="space-y-3 mb-8">
              <div className="flex items-center gap-3">
                <Check className="w-5 h-5 text-blue-400" />
                <span className="text-white">10% Discount</span>
              </div>
              <div className="flex items-center gap-3">
                <Check className="w-5 h-5 text-blue-400" />
                <span className="text-white">Gift Box</span>
              </div>
              <div className="flex items-center gap-3">
                <X className="w-5 h-5 text-slate-500" />
                <span className="text-slate-500">Projects Files</span>
              </div>
              <div className="flex items-center gap-3">
                <X className="w-5 h-5 text-slate-500" />
                <span className="text-slate-500">Weekends Included</span>
              </div>
            </div>

            <Button className="w-full bg-white text-slate-900 hover:bg-slate-100 rounded-full font-semibold">
              Get 10 Days
              <ArrowRight className="w-4 h-4 ml-2" />
            </Button>
          </Card>

          {/* 20 Days Pack */}
          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl relative">
            <div className="absolute top-4 right-4">
              <span className="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">€9,380</span>
            </div>
            <div className="mb-6">
              <h3 className="text-3xl font-bold mb-2 text-white">20 Days</h3>
              <p className="text-slate-400 mb-1">€469/day</p>
              <p className="text-slate-500 text-sm">Valid for 5 years</p>
            </div>

            <div className="space-y-3 mb-8">
              <div className="flex items-center gap-3">
                <Check className="w-5 h-5 text-blue-400" />
                <span className="text-white">15% Discount</span>
              </div>
              <div className="flex items-center gap-3">
                <Check className="w-5 h-5 text-blue-400" />
                <span className="text-white">Gift Box</span>
              </div>
              <div className="flex items-center gap-3">
                <Check className="w-5 h-5 text-blue-400" />
                <span className="text-white">Projects Files</span>
              </div>
              <div className="flex items-center gap-3">
                <Check className="w-5 h-5 text-blue-400" />
                <span className="text-white">Weekends Included</span>
              </div>
            </div>

            <Button className="w-full bg-white text-slate-900 hover:bg-slate-100 rounded-full font-semibold">
              Get 20 Days
              <ArrowRight className="w-4 h-4 ml-2" />
            </Button>
          </Card>
        </div>
      </div>

      {/* Addons section */}
      <div className="max-w-4xl mx-auto px-6 pb-16">
        <div className="text-center mb-12">
          <h2 className="text-4xl md:text-5xl font-bold mb-4">Addons</h2>
          <p className="text-slate-400 text-lg">Enhance your project with more options</p>
        </div>

        <div className="grid md:grid-cols-2 gap-6">
          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl">
            <h3 className="text-xl font-semibold mb-4 text-white">Extended Day</h3>
            <div className="flex items-center justify-between bg-white rounded-full p-2">
              <span className="text-slate-900 font-semibold px-4">+€79/hour</span>
              <Button size="sm" className="rounded-full bg-slate-900 hover:bg-slate-800">
                <ArrowRight className="w-4 h-4" />
              </Button>
            </div>
          </Card>

          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl">
            <h3 className="text-xl font-semibold mb-4 text-white">Project Files</h3>
            <div className="flex items-center justify-between bg-white rounded-full p-2">
              <span className="text-slate-900 font-semibold px-4">€99/year</span>
              <Button size="sm" className="rounded-full bg-slate-900 hover:bg-slate-800">
                <ArrowRight className="w-4 h-4" />
              </Button>
            </div>
          </Card>

          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl">
            <h3 className="text-xl font-semibold mb-4 text-white">Camera Package</h3>
            <div className="flex items-center justify-between bg-white rounded-full p-2">
              <span className="text-slate-900 font-semibold px-4">€249/day</span>
              <Button size="sm" className="rounded-full bg-slate-900 hover:bg-slate-800">
                <ArrowRight className="w-4 h-4" />
              </Button>
            </div>
          </Card>

          <Card className="bg-slate-800 border-slate-700 p-6 rounded-2xl">
            <h3 className="text-xl font-semibold mb-4 text-white">Website Content</h3>
            <div className="flex items-center justify-between bg-white rounded-full p-2">
              <span className="text-slate-900 font-semibold px-4">€349/project</span>
              <Button size="sm" className="rounded-full bg-slate-900 hover:bg-slate-800">
                <ArrowRight className="w-4 h-4" />
              </Button>
            </div>
          </Card>
        </div>
      </div>
    </div>
  )
}
